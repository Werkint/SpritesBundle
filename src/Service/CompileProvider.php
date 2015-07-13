<?php
namespace Werkint\Bundle\SpritesBundle\Service;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLoggerInterface;
use Werkint\Bundle\CommandBundle\Service\Processor\Compile\CompileProviderInterface;

/**
 * CompileProvider.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CompileProvider implements
    CompileProviderInterface
{
    protected $service;

    /**
     * @param Sprites $service
     */
    public function __construct(
        Sprites $service
    ) {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null
    ) {
        $num = $this->service->compile();
        $logger->writeln($num . ' images merged');
    }
}
