<?php
namespace Werkint\Bundle\SpritesBundle\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Bundle\SpritesBundle\WerkintSpritesBundle;

/**
 * WerkintSpritesBundleTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesdBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testPasses()
    {
        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $class = 'Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface';
        $containerBuilderMock->expects($this->exactly(1))
            ->method('addCompilerPass')
            ->with($this->isInstanceOf($class))
            ->will($this->returnValue(true));
        $obj = new WerkintSpritesBundle();
        $obj->build($containerBuilderMock);
    }

}
