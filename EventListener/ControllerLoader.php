<?php
namespace Werkint\Bundle\SpritesBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * ControllerLoader.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ControllerLoader
{
    // -- Services ---------------------------------------

    protected function serviceWebapp()
    {
        return $this->container->get('werkint.webapp');
    }

    // -- Actions ---------------------------------------

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function par($name)
    {
        return $this->container->getParameter($name);
    }

    public function initController(FilterControllerEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        if (!$this->container->has('werkint.webapp')) {
            return;
        }

        $config = $this->par('werkint_sprites');
        $webapp = $this->serviceWebapp();

        $webapp->getLoader()->blockStart('_root');

        $webapp->attachFile($config['styles']);
        $webapp->addVar('sprites-namespace', $config['namespace']);
        $webapp->addVar('sprites-path', $config['path']);

        $webapp->getLoader()->blockStart('page');
    }

}
