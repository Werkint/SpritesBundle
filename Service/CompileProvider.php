<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Werkint\Bundle\CommandBundle\Service\Contract\CompileProviderInterface;

/**
 * CompileProvider.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CompileProvider implements
    CompileProviderInterface
{
    protected $service;

    public function __construct(
        Sprites $service
    ) {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function process(
        OutputInterface $out,
        ContainerAwareCommand $command = null
    ) {
        $num = $this->service->compile();
        $out->writeln($num . ' images merged');
    }
}
