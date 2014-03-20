<?php
namespace Werkint\Bundle\SpritesBundle\Tests\Service\Twig;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Bundle\SpritesBundle\Service\Twig\WerkintSpritesExtension;
use Werkint\Bundle\SpritesBundle\WerkintSpritesBundle;

/**
 * WerkintSpritesExtensionTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSpritesExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testFunction()
    {
        $obj = new WerkintSpritesExtension('ns');
        $this->assertEquals('werkint_sprites', $obj->getName());
        $fn = $obj->getFunction('sprite');
        $this->assertInternalType('callable', $fn);
        $this->assertTrue(strpos($fn('foo', 'bar'), 'ns-foo') !== false);
    }

}
