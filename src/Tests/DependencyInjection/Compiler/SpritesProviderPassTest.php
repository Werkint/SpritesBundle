<?php
namespace Werkint\Bundle\MoneyBundle\Tests\DependencyInjection\Compiler;

use Werkint\Bundle\SpritesBundle\DependencyInjection\Compiler\SpritesProviderPass;

/**
 * SpritesProviderPassTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SpritesProviderPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessWithoutProviderDefinition()
    {
        $renderersPass = new SpritesProviderPass();

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertNull($renderersPass->process(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder')
        ));
    }

    public function testProcess()
    {
        $definitionMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock();
        $definitionMock->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('addProvider'), $this->isType('array'));

        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->with($this->equalTo(SpritesProviderPass::CLASS_SRV))
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo(SpritesProviderPass::CLASS_SRV))
            ->will($this->returnValue($definitionMock));
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo(SpritesProviderPass::CLASS_TAG))
            ->will($this->returnValue(['provider_tag1' => [['class' => 'test']]]));

        $renderersPass = new SpritesProviderPass();
        $renderersPass->process($containerBuilderMock);
    }

}
