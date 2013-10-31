<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Werkint\Bundle\WebappBundle\Twig\AbstractExtension;

class TwigExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_sprites';

    public function __construct(
        ContainerInterface $cont
    ) {
        $namespace = $cont->getParameter('werkint_sprites_namespace');
        $this->addFunction('sprite', true, function ($name, $class) use (&$namespace) {
            return '<span class="' . $namespace . ' ' . $namespace . '-' . $name . ' ' . $class . '">' . $class . ' icon</span>';
        });
    }

}
