<?php
namespace Werkint\Bundle\SpritesBundle\Service\Twig;

use Werkint\Bundle\FrameworkExtraBundle\Twig\AbstractExtension;

/**
 * WerkintSpritesExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_sprites';

    protected $namespace;

    /**
     * @param string $namespace
     */
    public function __construct(
        $namespace
    ) {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->addFunction('sprite', true, function (
            $name,
            $class
        ) {
            $class = $this->namespace . ' ' . $this->namespace . '-' . $name . ' ' . $class;
            return '<span class="' . $class . '">' . $class . ' icon</span>';
        });
    }
}
