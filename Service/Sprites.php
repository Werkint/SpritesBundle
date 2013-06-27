<?php
namespace Werkint\Bundle\SpritesBundle\Service;

class Sprites
{
    protected $template;
    protected $imgDir;
    protected $imgPath;
    protected $stylePath;

    public function __construct(
        array $params,
        $scriptsDir
    ) {
        $this->template = $scriptsDir . '/spritesTemplate.scss';
        $this->imgDir = $params['dir'];
        $this->imgPath = $params['path'];
        $this->stylePath = $params['styles'];
    }

    protected function getSizes()
    {
        // STUB
        return [];
    }

    protected $providers = [];

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

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

    public function compile()
    {
        $this->clearCache();
        $data = $this->getList();
        $num = 0;
        $scss = [];
        $sizes = $this->getSizes();
        foreach ($data as $name => $list) {
            $num += count($list);
            $scss[] = $this->compileFile($name, $list, $sizes[$name]);
        }
        $template = file_get_contents($this->template);
        $scss = str_replace(
            '/*[[THEDATA]]*/', join("\n", $scss), $template
        );
        foreach ($data as $name => $list) {
            $scss .= '.#{$const-sprites-namespace}-' . $name . ' {' . "\n";
            $scss .= '@include sprite-' . $name . ';' . "\n";
            $scss .= '};' . "\n";
        }
        file_put_contents($this->stylePath, $scss);

        return $num;
    }

    /**
     * Компилирует спрайт
     * @param  string $name Имя файла/класс для спрайта
     * @param  array  $list Список изображений
     * @param  int    $size Ширина спрайта
     * @return string
     */
    protected function compileFile($name, array $list, $size = 100)
    {
        $count = count($list);
        $img = imagecreatetruecolor($size, $size * $count);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagefilledrectangle(
            $img, 0, 0, $size, $size * $count,
            imagecolorallocatealpha($img, 255, 255, 255, 127)
        );
        $fname = $name . '_' . substr(sha1($name . microtime(true)), 0, 10);

        $scss = [];
        $scss[] = '@mixin sprite-' . $name . ' {';
        $scss[] = '@include sprites-icon-general(\'' . $fname . '\');';
        $num = 0;
        foreach ($list as $class => $imgname) {
            $tile = imagecreatefrompng($imgname);
            imagealphablending($tile, true);
            imagecopyresampled(
                $img, $tile, 0, $num * $size, 0, 0,
                $size, $size, imagesx($tile), imagesy($tile)
            );
            imagedestroy($tile);
            $scss[] = '@include sprites-icon-chunk(\'' . $class . '\', ' . $count . ', ' . $num . ');';
            $num++;
        }
        $scss[] = '}';
        $scss = join("\n", $scss);

        $filename = $this->imgDir . '/' . $fname . '.png';
        imagepng($img, $filename);
        imagedestroy($img);

        return $scss;
    }

    protected function clearCache()
    {
        $dir = opendir($this->imgDir);
        while ($file = readdir($dir)) {
            if (substr($file, 0, 1) == '.') {
                continue;
            }
            if (is_file($this->imgDir . '/' . $file)) {
                unlink($this->imgDir . '/' . $file);
            }
        }
    }
}
