<?php
namespace Werkint\Bundle\SpritesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * WerkintSpritesExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesExtension extends Extension
{
    public function load(
        array $configs,
        ContainerBuilder $container
    ) {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration($this->getAlias()),
            $configs
        );
        $container->setParameter(
            $this->getAlias(),
            $config
        );
        $container->setParameter(
            $this->getAlias() . '_namespace',
            $config['namespace']
        );
        $container->setParameter(
            $this->getAlias() . '_template',
            __DIR__ . '/../Resources/scripts/spritesTemplate.scss'
        );
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }
}
