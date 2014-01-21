<?php
namespace Werkint\Bundle\SpritesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * SpritesProviderPass.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SpritesProviderPass implements
    CompilerPassInterface
{
    const CLASS_SRV = 'werkint.sprites';
    const CLASS_TAG = 'werkint.sprites.provider';

    /**
     * {@inheritdoc}
     */
    public function process(
        ContainerBuilder $container
    ) {
        if (!$container->hasDefinition(static::CLASS_SRV)) {
            return;
        }
        $definition = $container->getDefinition(
            static::CLASS_SRV
        );

        $list = $container->findTaggedServiceIds(static::CLASS_TAG);
        foreach ($list as $id => $attributes) {
            $definition->addMethodCall(
                'addProvider', [
                    new Reference($id),
                ]
            );
        }
    }

}
