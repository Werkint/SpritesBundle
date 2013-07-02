<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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
    const NAMESPACE_NAME = 'const-sprites-namespace';
    // Should we create global classes for mixins?
    const CREATE_CLASSES = true;

    /**
     * Path to the template
     * @var string $template
     */
    protected $template;
    /**
     * Directory to save sprites
     * @var string $imgDir
     */
    protected $imgDir;
    /**
     * Path to images for SCSS mixins
     * @var string $imgPath
     */
    protected $imgPath;
    /**
     * Where SCSS mixins should be saved
     * @var string $stylePath
     */
    protected $stylePath;

    public function __construct(
        array $params,
        $template
    ) {
        $this->template = $template;
        $this->imgDir = $params['dir'];
        $this->imgPath = $params['path'];
        $this->stylePath = $params['styles'];
    }

    /**
     * Returns list of size-bindings for sprites
     * @return array
     */
    protected function getSizes()
    {
        return [];
    }

    /**
     * Returns list of images to merge
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
     * @return int Number of images merged
     */
    public function compile()
    {
        // Path prefix
        $prefix = $this->getNewPrefix();
        // List of images
        $data = $this->getList();
        // Sizes of images
        $sizes = $this->getSizes();

        $num = 0;
        $scss = [];
        foreach ($data as $name => $list) {
            // Sprite size
            $size = isset($sizes[$name]) ? $sizes[$name] : 100;
            $scss[] = $this->compileFile($prefix, $name, $list, $size);
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
                $scss .= '.#{$' . static::NAMESPACE_NAME . '}-' . $name . ' {' . "\n";
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
     * @param  string $prefix Path prefix
     * @param  string $name   Filename/class for sprite
     * @param  array  $list   Image list
     * @param  int    $size   Sprite width
     * @return string
     */
    protected function compileFile($prefix, $name, array $list, $size)
    {
        // Sprite path
        $fname = $prefix . '/' . $name;
        // Tile border and size
        $border = static::BORDER_WIDTH;
        $tileSize = $size - $border * 2;
        // Amount of tiles
        $count = count($list);

        // Sprite image
        $img = new \Imagick();
        $img->newImage($size, $size * $count, 'transparent', 'png');

        // SCSS Header
        $scss = [];
        $scss[] = '@mixin ' . static::PREFIX_SPRITE . $name . ' {';
        $scss[] = '@include ' . static::PREFIX_ICON . 'general(\'' . $fname . '\');';

        // Merging images
        $num = 0;
        foreach ($list as $class => $imgname) {
            $tile = new \Imagick($imgname);
            $tile->resizeimage($tileSize, $tileSize, \Imagick::FILTER_CUBIC, 0.9);

            // We add each image to the sprite with a border
            $img->compositeimage(
                $tile,
                \Imagick::COMPOSITE_ADD,
                $border, $num * $size + $border
            );

            $scss[] = '@include ' . static::PREFIX_ICON . 'chunk(\'' . $class . '\', ' . $count . ', ' . $num . ');';
            $num++;
        }

        // SCSS Footer
        $scss[] = '}';
        $scss = join("\n", $scss);

        // Writing image
        $filename = $this->imgDir . '/' . $fname . '.png';
        file_put_contents(
            $filename, $img->getimageblob()
        );

        return $scss;
    }

    /**
     * Creates new prefix for sprites
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

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }
}
