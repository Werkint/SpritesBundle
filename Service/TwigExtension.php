<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Werkint\Bundle\WebappBundle\Twig\AbstractExtension;

/**
 * TwigExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class TwigExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_sprites';

    /**
     * @param string $namespace
     */
    public function __construct(
        $namespace
    ) {
        $this->addFunction('sprite', true, function ($name, $class) use (&$namespace) {
            return '<span class="' . $namespace . ' ' . $namespace . '-' . $name . ' ' . $class . '">' . $class . ' icon</span>';
        });
    }

}
