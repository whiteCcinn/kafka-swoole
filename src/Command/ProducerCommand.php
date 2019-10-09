<?php
declare(strict_types=1);

namespace Kafka\Command;

use Kafka\Event\StartBeforeEvent;
use Kafka\Manager\MetadataManager;
use Kafka\Protocol\Request\Produce\DataProduce;
use Kafka\Protocol\Request\Produce\MessageProduce;
use Kafka\Protocol\Request\Produce\MessageSetProduce;
use Kafka\Protocol\Request\Produce\TopicDataProduce;
use Kafka\Protocol\Request\ProduceRequest;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\KafkaCServer;
use Kafka\Socket\Socket;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProducerCommand extends Command
{
    protected static $defaultName = 'kafka.produce';

    protected function configure()
    {
        $this
            ->setDescription('Send a message')
            ->setHelp('This command will help you send separate messages to a topic...')
            ->addOption(
                'topic',
                't',
                InputOption::VALUE_OPTIONAL,
                'Which is the topic you want to send?'
            )->addArgument(
                'message',
                InputArgument::REQUIRED,
                'The message you wish to send.'
            );

    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $topic = $input->getOption('topic');
        $message = $input->getArgument('message');
        $protocol = new ProduceRequest();
        $protocol->setAcks(Int16::value(1))
                 ->setTimeout(Int32::value(1 * 1000))
                 ->setTopicData([
                     (new TopicDataProduce())->setTopic(String16::value($topic))
                                             ->setData([
                                                 (new DataProduce())->setPartition(Int32::value(0))
                                                                    ->setMessageSet([
                                                                        (new MessageSetProduce())->setOffset(Int64::value(0))
                                                                                                 ->setMessage(
                                                                                                     (new MessageProduce())->setValue(Bytes32::value($message))
                                                                                                 ),

                                                                    ])
                                             ])
                 ]);
        $data = $protocol->pack();
        $socket = new Socket();
        $socket->connect('mkafka3', 9092)->send($data);
        $socket->revcByKafka($protocol);
        var_dump($protocol->response->toArray());
    }
}
