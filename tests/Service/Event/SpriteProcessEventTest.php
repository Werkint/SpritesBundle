<?php
namespace Werkint\Bundle\SpritesBundle\Tests\Service\Event;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Bundle\SpritesBundle\Service\Event\SpriteProcessEvent;
use Werkint\Bundle\SpritesBundle\Service\TwigExtension;
use Werkint\Bundle\SpritesBundle\WerkintSpritesBundle;

/**
 * SpriteProcessEventTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SpriteProcessEventTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $var1 = new \Imagick();
        $var2 = 'test2';
        $var3 = 'test3';
        $obj = new SpriteProcessEvent(
            $var1,
            $var2,
            $var3
        );

        $var3 = 'test1';
        $this->assertEquals($var1, $obj->getImage());
        $this->assertEquals($var2, $obj->getSpriteName());
        $this->assertEquals($var3, $obj->getScss());
    }

}
