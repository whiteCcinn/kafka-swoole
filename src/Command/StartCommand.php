<?php
declare(strict_types=1);

namespace Kafka\Command;

use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Event\StartBeforeEvent;
use Kafka\Manager\MetadataManager;
use Kafka\Protocol\Request\Common\RequestHeader;
use Kafka\Protocol\Request\MetadataRequest;
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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
//        $protocol = new MetadataRequest();
//        $protocol->setTopicName([String16::value('caiwenhui')]);

        $protocol = new ProduceRequest();
        $protocol->setAcks(Int16::value(1))
                 ->setTimeout(Int32::value(1 * 1000))
                 ->setTopicData([
                     (new TopicDataProduce())->setTopic(String16::value('test'))
                                             ->setData([
                                                 (new DataProduce())->setPartition(Int32::value(0))
                                                                    ->setMessageSet([
                                                                        (new MessageSetProduce())->setOffset(Int64::value(0))
                                                                                                 ->setMessage(
                                                                                                     (new MessageProduce())->setValue(Bytes32::value('test...'))
                                                                                                 )
                                                                    ])
                                             ])
                 ]);
        $data = $protocol->pack();
        dispatch(new StartBeforeEvent(), StartBeforeEvent::NAME);
        $metadataManager = new MetadataManager();
        $processNum = $metadataManager->calculationClientNum();
        KafkaCServer::getInstance()->setProcess($processNum)->start();
    }
}
