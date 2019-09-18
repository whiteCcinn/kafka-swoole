<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use App\App;
use Kafka\Config\CommonConfig;
use Kafka\Event\StartAfterEvent;
use Kafka\Event\StartBeforeEvent;
use Kafka\Kafka;
use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Response\Metadata\PartitionMetadata;
use Kafka\Protocol\Response\Metadata\TopicMetadata;
use Kafka\Protocol\Response\MetadataResponse;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class StartSubscriber
 *
 * @package App\Subscriber
 */
class StartSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            StartBeforeEvent::NAME => 'onStartBefore',
            StartAfterEvent::NAME  => 'onStartAfter',
        ];
    }

    public function onStartBefore(): void
    {
        // step1. get all broker info
        App::$commonConfig = new CommonConfig();
        foreach (explode(',', App::$commonConfig->getMetadataBrokerList()) as $hostPorts) {
            [$host, $port] = explode(':', $hostPorts);
            $protocol = new MetadataRequest();
            $fn1 = function () use ($protocol) {
                $protocol = new MetadataRequest();
                $topics = App::$commonConfig->getTopicNames();
                $topicNames = array_map(function ($item) {
                    return String16::value($item);
                }, explode(',', $topics));
                $protocol->setTopicName($topicNames);

                return $protocol->pack();
            };
            $fn2 = function (string $data) use ($protocol) {
                $protocol->response->unpack($data);

                return $protocol;
            };
            $ret = SocketServer::getInstance()->run($host, (int)$port, $fn1, $fn2);
            if (!$ret) {
                continue;
            }
            /** @var MetadataResponse $response */
            $response = $protocol->response;
            Kafka::getInstance()->setBrokers(toValue($response->getBrokers()));
            Kafka::getInstance()->setTopics(toValue($response->getTopics()));
            $partitions = [];
            /** @var TopicMetadata $topicMetadata */
            foreach ($response->getTopics() as $topicMetadata) {
                /** @var PartitionMetadata $partitionMetadata */
                foreach ($topicMetadata->getPartitions() as $partitionMetadata) {
                    $partitions[$topicMetadata->getName()->getValue()][] = $partitionMetadata->getPartitionIndex();
                }
            }
            Kafka::getInstance()->setPartitions($partitions);
            var_dump($partitions);exit;
            break;
        }
    }

    public function onStartAfter(): void
    {
        // do something
    }
}