<?php
namespace Werkint\Bundle\SpritesBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
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

    protected $init = false;

    /**
     * @param GetResponseEvent $event
     * @return bool
     */
    public function onKernelController(GetResponseEvent $event)
    {
        if ($this->init) {
            return false;
        }
        $this->init = true;

        if (!$this->webapp) {
            return false;
        }

        $this->webapp->getLoader()->blockStart('_root');

        $this->webapp->attachFile($this->config['styles']);
        $this->webapp->addVar('webapp-sprites-namespace', $this->config['namespace']);
        $this->webapp->addVar('webapp-sprites-path', $this->config['path']);

        $this->webapp->getLoader()->blockStart('page');
    }

}
