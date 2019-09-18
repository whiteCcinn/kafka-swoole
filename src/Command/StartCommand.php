<?php

namespace Kafka\Command;

use Kafka\Event\StartBeforeEvent;
use Kafka\Server\KafkaCServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Swoole\Server;

class StartCommand extends Command
{
    protected static $defaultName = 'start';

    protected function configure()
    {
        $this
            ->setDescription('Start Kafka-Swoole')
            ->setHelp('This command allows Start Kafka-Swoole-Server...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dispatch(new StartBeforeEvent(), StartBeforeEvent::NAME);
        $server = KafkaCServer::getInstance()->getServer();
    }
}
