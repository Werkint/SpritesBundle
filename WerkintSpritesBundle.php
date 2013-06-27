<?php
namespace Werkint\Bundle\SpritesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Bundle\SpritesBundle\Service\SpritesCompilerPass;

class WerkintSpritesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SpritesCompilerPass);
    }
}
