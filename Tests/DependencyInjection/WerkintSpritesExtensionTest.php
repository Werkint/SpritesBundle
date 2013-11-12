<?php
namespace Werkint\Bundle\SpritesBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Bundle\SpritesBundle\DependencyInjection\WerkintSpritesExtension;

/**
 * WerkintSpritesExtensionTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testRequiredConfig()
    {
        $this->loadContainer([]);
    }

    public function testConfig()
    {
        $container = $this->loadContainer([
            'dir'    => '',
            'path'   => '',
            'styles' => '',
        ]);

        $this->assertTrue($container->hasParameter('werkint_sprites'));
        $this->assertEquals('global-sprite', $container->getParameter('werkint_sprites_namespace'));
    }

    public function testServices()
    {
        $container = $this->loadContainer([
            'dir'    => '',
            'path'   => '',
            'styles' => '',
        ]);

        $this->assertTrue(
            $container->hasDefinition('werkint.sprites'),
            'Main service is loaded'
        );
    }

    protected function loadContainer(array $config)
    {
        $container = new ContainerBuilder();
        $loader = new WerkintSpritesExtension();
        $loader->load([$config], $container);
        return $container;
    }
}
