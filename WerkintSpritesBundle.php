<?php
namespace Werkint\Bundle\SpritesBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Werkint\Bundle\SpritesBundle\Service\SpritesCompilerPass;

/**
 * WerkintSpritesBundle.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SpritesCompilerPass);
    }
}
