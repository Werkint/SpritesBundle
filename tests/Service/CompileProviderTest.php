<?php
namespace Werkint\Bundle\SpritesBundle\Tests\Service;

use Symfony\Component\Console\Output\NullOutput;
use Werkint\Bundle\FrameworkExtraBundle\Command\CompileCommand;
use Werkint\Bundle\SpritesBundle\Service\CompileProvider;

/**
 * CompileProviderTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CompileProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $sprites = $this->getMock(
            'Werkint\Bundle\SpritesBundle\Service\Sprites',
            ['compile'], [], '', false
        );
        $sprites
            ->expects($this->once())
            ->method('compile');
        $obj = new CompileProvider($sprites);
        $obj->process(
            new NullOutput(),
            new CompileCommand()
        );
    }

}
