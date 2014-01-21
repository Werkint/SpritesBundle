<?php
namespace Werkint\Bundle\SpritesBundle\Tests\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Werkint\Bundle\SpritesBundle\EventListener\ControllerLoader;

/**
 * ControllerLoaderTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ControllerLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ControllerLoader */
    protected $listener;

    public function setUp()
    {
        $loader = $this->getMock(
            'Werkint\Bundle\WebappBundle\Webapp\ScriptLoader'
        );
        $webapp = $this->getMock(
            'Werkint\Bundle\WebappBundle\Webapp\WebappInterface',
            ['attachFile', 'addVar', 'getLoader']
        );
        $webapp
            ->expects($this->any())
            ->method('getLoader')
            ->will($this->returnValue($loader));
        $this->listener = new ControllerLoader($webapp, [
            'styles'    => '',
            'namespace' => '',
            'path'      => '',
        ]);
    }

    public function testSkip()
    {
        $req = $this->listener;

        $event = $this->getEvent();
        $this->assertFalse($req->onKernelController($event));
        $obj = new ControllerLoader(null, []);
        $event = $this->getEvent(true);
        $this->assertFalse($obj->onKernelController($event));
    }

    public function testProcess()
    {
        $req = $this->listener;
        $event = $this->getEvent(true);
        $this->assertNull($req->onKernelController($event));
    }

    /**
     * @param bool        $real
     * @param string|null $route
     * @return FilterControllerEvent
     */
    protected function getEvent($real = false, $route = null)
    {
        $event = $this->getMock(
            'Symfony\Component\HttpKernel\Event\FilterControllerEvent',
            [], [], '', false
        );

        $type = $real ? HttpKernelInterface::MASTER_REQUEST :
            HttpKernelInterface::SUB_REQUEST;
        $event
            ->expects($this->any())
            ->method('getRequestType')
            ->will($this->returnValue($type));

        return $event;
    }

}
