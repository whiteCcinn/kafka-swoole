<?php
declare(strict_types=1);

namespace Kafka\Command;

use Kafka\Event\StartBeforeEvent;
use Kafka\Manager\MetadataManager;
use Kafka\Server\KafkaCServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DemoCommand extends Command
{
    protected static $defaultName = 'demo';

    protected function configure()
    {
        $this
            ->setDescription('Your command description')
            ->setHelp('Your command help');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // do your command you want to do
        echo 'Hello, Kafka-swoole';
    }
}
