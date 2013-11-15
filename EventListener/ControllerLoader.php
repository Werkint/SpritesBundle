<?php
namespace Werkint\Bundle\SpritesBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Werkint\Bundle\WebappBundle\Webapp\WebappInterface;

/**
 * ControllerLoader.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ControllerLoader
{
    protected $config;
    protected $webapp;

    /**
     * @param WebappInterface $webapp
     * @param array           $config
     */
    public function __construct(
        WebappInterface $webapp = null,
        array $config
    ) {
        $this->webapp = $webapp;
        $this->config = $config;
    }

    /**
     * @param FilterControllerEvent $event
     * @return bool
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return false;
        }

        if (!$this->webapp) {
            return false;
        }

        $this->webapp->getLoader()->blockStart('_root');

        $this->webapp->attachFile($this->config['styles']);
        $this->webapp->addVar('sprites-namespace', $this->config['namespace']);
        $this->webapp->addVar('sprites-path', $this->config['path']);

        $this->webapp->getLoader()->blockStart('page');
    }

}
