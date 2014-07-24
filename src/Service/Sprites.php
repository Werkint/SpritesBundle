<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Werkint\Bundle\SpritesBundle\Service\Contract\ProviderInterface;
use Werkint\Bundle\SpritesBundle\Service\Contract\SizeProviderInterface;

/**
 * Sprites.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Sprites
{
    // Transparent padding around images
    const BORDER_WIDTH = 5;
    // String to replace in the template
    const TEMPLATE_NEEDLE = '/*[[THEDATA]]*/';
    // Prefix for separate images
    const PREFIX_ICON = 'sprites-icon-';
    // Prefix for each sprite's mixin
    const PREFIX_SPRITE = 'sprite-';
    // Prefix for the whole thing
    const NAMESPACE_NAME = 'webapp-sprites-namespace';
    // Should we create global classes for mixins?
    const CREATE_CLASSES = true;
    const EVENT_PREFIX = 'werkint.sprites.';

    protected $dispatcher;

    /**
     * Path to the template
     *
     * @var string $template
     */
    protected $template;
    /**
     * Directory to save sprites
     *
     * @var string $imgDir
     */
    protected $imgDir;
    /**
     * Path to images for SCSS mixins
     *
     * @var string $imgPath
     */
    protected $imgPath;
    /**
     * Where SCSS mixins should be saved
     *
     * @var string $stylePath
     */
    protected $stylePath;

    // sizes
    protected $sizeDefault;
    protected $sizes;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param array                    $params
     * @param string                   $template
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        array $params,
        $template
    ) {
        $this->dispatcher = $dispatcher;
        $this->template = $template;
        $this->imgDir = $params['dir'];
        $this->imgPath = $params['path'];
        $this->stylePath = $params['styles'];
        $this->sizeDefault = $params['defaultsize'];
        $this->sizes = $params['sizes'];
    }

    /**
     * Returns size
     *
     * @param string   $name
     * @param int|null $default
     * @throws \Exception
     * @return int
     */
    protected function getSize($name, $default = null)
    {
        if (!$default) {
            $default = $this->sizeDefault;
        }
        $size = isset($this->sizes[$name]) ? $this->sizes[$name] : $default;
        if (!$size) {
            throw new \Exception('Wrong size of ' . $name);
        }

        return $size;
    }

    /**
     * Returns list of images to merge
     *
     * @return array
     */
    public function getList()
    {
        $data = [];
        foreach ($this->providers as $provider) {
            /** @var ProviderInterface $provider */
            $data = array_merge_recursive(
                $data, $provider->getImages()
            );
        }

        return $data;
    }

    /**
     * Compiles all sprites
     *
     * @return int Number of images merged
     */
    public function compile()
    {
        // Path prefix
        $prefix = $this->getNewPrefix();
        // List of images
        $data = $this->getList();

        // sizes
        $sizes = [];
        foreach ($this->providers as $provider) {
            if ($provider instanceof SizeProviderInterface) {
                $sizes = array_merge($sizes, $provider->getSizes());
            }
        }

        $num = 0;
        $scss = [];
        foreach ($data as $name => $list) {
            // Sprite size
            $size = !isset($sizes[$name]) ? null : $sizes[$name];
            $size = $this->getSize($name, $size);
            if (is_array($size)) {
                $border = $size[1];
                $sizeH = isset($size[2]) ? $size[2] : $size[0];
                $sizeW = $size[0];
            } else {
                $border = static::BORDER_WIDTH;
                $sizeH = $sizeW = $size;
            }
            $scss[] = $this->compileFile($prefix, $name, $list, [$sizeW, $sizeH], $border);
            $num += count($list);
        }

        // Populating template
        $template = file_get_contents($this->template);
        $scss = str_replace(
            static::TEMPLATE_NEEDLE, join("\n", $scss), $template
        );

        // Creating global classes
        if (static::CREATE_CLASSES) {
            foreach ($data as $name => $list) {
                $scss .= '.#{$' . static::NAMESPACE_NAME . '}';
                $scss .= ', .#{$' . static::NAMESPACE_NAME . '}-' . $name . ' {' . "\n";
                $scss .= '@include ' . static::PREFIX_SPRITE . $name . ';' . "\n";
                $scss .= '};' . "\n";
            }
        }

        // Writing the file
        file_put_contents($this->stylePath, $scss);

        return $num;
    }

    /**
     * Compiles sprite
     *
     * @param string      $prefix Path prefix
     * @param string      $name   Filename/class for sprite
     * @param array       $list   Image list
     * @param array|int[] $size   Sprite 0=>width/1=>height
     * @param int         $border
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function compileFile($prefix, $name, array $list, array $size, $border)
    {
        if (!($size[0] && $size[1])) {
            throw new \InvalidArgumentException('Empty size');
        }

        // Sprite path
        $fname = $prefix . '/' . $name;
        // Tile size
        $tileSizeW = $size[0] - $border * 2;
        $tileSizeH = $size[1] - $border * 2;
        $tileSizeP = $tileSizeH / $tileSizeW;

        // Amount of tiles
        $count = count($list);

        // Sprite image
        // Sprite image
        $img = new \Imagick();
        $img->newImage($size[0], $size[1] * $count, 'transparent', 'png');

        // SCSS Header
        $scss = [];
        $scss[] = '@mixin ' . static::PREFIX_SPRITE . $name . '($type: \'general\', $forceClass: false) {';

        // Merging images
        $num = 0;
        $classes = [];
        foreach ($list as $class => $imgname) {
            $tile = new \Imagick($imgname);

            $deltaX = $border;
            $deltaY = $num * $size[1] + $border;
            $tmpSizeW = $tile->getimagewidth();
            $tmpSizeH = $tile->getimageheight();
            $tmpSizeP = $tmpSizeH / $tmpSizeW;
            if ($tmpSizeP > $tileSizeP) {
                $tmpSizeH = $tileSizeH;
                $tmpSizeW = $tileSizeH / $tmpSizeP;
                $deltaX += ($tileSizeW - $tmpSizeW) / 2;
            } else {
                $tmpSizeW = $tileSizeW;
                $tmpSizeH = $tileSizeW * $tmpSizeP;
                $deltaY += ($tileSizeH - $tmpSizeH) / 2;
            }

            $tile->resizeimage($tmpSizeW, $tmpSizeH, \Imagick::FILTER_CUBIC, 0.9);
            $this->dispatcher->dispatch(
                static::EVENT_PREFIX . 'tile',
                new Event\TileProcessEvent($tile, $name, $imgname)
            );


            // We add each image to the sprite with a border
            $img->compositeimage(
                $tile, \Imagick::COMPOSITE_ADD, $deltaX, $deltaY
            );

            $classes[] = $class;
            $num++;
        }
        $scss[] = '  $icons: ' . join(' ', $classes) . ';';
        $scss[] = '  @include sprites-sprite-generic($icons, \'' . $fname . '\', $type, $forceClass);';

        // SCSS Footer
        $scss[] = '}';
        $scss = join("\n", $scss);
        $this->dispatcher->dispatch(
            static::EVENT_PREFIX . 'sprite',
            new Event\SpriteProcessEvent($img, $name, $scss)
        );

        // Writing image
        $filename = $this->imgDir . '/' . $fname . '.png';
        file_put_contents(
            $filename, $img->getimageblob()
        );

        return $scss;
    }

    /**
     * Creates new prefix for sprites
     *
     * @return string
     */
    protected function getNewPrefix()
    {
        // Removing old sprites
        $find = new Finder();
        $fs = new Filesystem();
        $fs->remove(
            $find->in($this->imgDir)->directories(),
            $this->stylePath
        );

        // New prefix
        $prefix = substr(sha1(microtime(true) . 'sprites'), 1, 10);
        $fs->mkdir($this->imgDir . '/' . $prefix);

        return $prefix;
    }

    // -- Providers ---------------------------------------

    protected $providers = [];

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(
        ProviderInterface $provider
    ) {
        $this->providers[] = $provider;
    }
}
