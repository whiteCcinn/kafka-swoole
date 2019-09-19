<?php

namespace Kafka\Manager;

use App\App;
use Kafka\Config\CommonConfig;
use Kafka\Exception\InvalidEnvException;
use Kafka\Kafka;
use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Response\Metadata\PartitionMetadata;
use Kafka\Protocol\Response\Metadata\TopicMetadata;
use Kafka\Protocol\Response\MetadataResponse;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;

class MetadataManager
{
    /**
     * register metadata info
     */
    public function registerMetadataInfo()
    {
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
                    $partitions[$topicMetadata->getName()->getValue()][] = $partitionMetadata->getPartitionIndex()
                                                                                             ->getValue();
                }
            }
            Kafka::getInstance()->setPartitions($partitions);
            break;
        }
    }

    /**
     * @return int
     * @throws InvalidEnvException
     */
    public function calculationClientNum(): int
    {
        $partitions = Kafka::getInstance()->getPartitions();
        $partitionNum = [];
        foreach ($partitions as $topic => $partition) {
            $partitionNum[] = count($partition);
        }
        $minNum = min($partitionNum);
        $maxNum = max($partitionNum);
        $envNum = env('KAFKA_CLIENT_CONSUMER_NUM');
        if ($envNum > $maxNum) {
            throw new InvalidEnvException(trans('manager.metadata.max.consumer', [$maxNum, $envNum]));
        }

        if ($envNum <= $minNum) {
            $num = $envNum;
        } elseif ($minNum > 2) {
            $num = floor($minNum / 2);
        } else {
            $num = 1;
        }

        return $num;
    }
}