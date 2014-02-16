<?php
namespace Werkint\Bundle\SpritesBundle\Tests\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Bundle\SpritesBundle\Service\TwigExtension;
use Werkint\Bundle\SpritesBundle\WerkintSpritesBundle;

/**
 * TwigExtensionTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class TwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testFunction()
    {
        $obj = new TwigExtension('ns');
        $this->assertEquals('werkint_sprites', $obj->getName());
        $fn = $obj->getFunction('sprite');
        $this->assertInternalType('callable', $fn);
        $this->assertTrue(strpos($fn('foo', 'bar'), 'ns-foo') !== false);
    }

}
