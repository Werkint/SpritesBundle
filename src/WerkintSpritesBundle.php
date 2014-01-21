<?php
namespace Werkint\Bundle\SpritesBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Werkint\Bundle\SpritesBundle\DependencyInjection\Compiler\SpritesProviderPass;

/**
 * WerkintSpritesBundle.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SpritesProviderPass);
    }
}
