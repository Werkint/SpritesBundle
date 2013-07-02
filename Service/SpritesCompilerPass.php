<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * SpritesCompilerPass.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SpritesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('werkint.sprites')) {
            return;
        }

        $definition = $container->getDefinition('werkint.sprites');

        $list = $container->findTaggedServiceIds('werkint.sprites.provider');
        foreach ($list as $id => $attributes) {
            $definition->addMethodCall(
                'addProvider', [new Reference($id)]
            );
        }
    }

}
