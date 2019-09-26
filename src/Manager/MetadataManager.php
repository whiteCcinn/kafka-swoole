<?php

namespace Kafka\Manager;

use App\App;
use Kafka\Config\CommonConfig;
use Kafka\Config\ConsumerConfig;
use Kafka\Config\ProducerConfig;
use Kafka\Exception\InvalidEnvException;
use Kafka\Exception\Socket\NormalSocketConnectException;
use Kafka\Kafka;
use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Response\Metadata\PartitionMetadata;
use Kafka\Protocol\Response\Metadata\TopicMetadata;
use Kafka\Protocol\Response\MetadataResponse;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use Kafka\Socket\NormalSocket;
use Kafka\Socket\Socket;
use Kafka\Support\SingletonTrait;

class MetadataManager
{
    use SingletonTrait;

    /**
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function registerMetadataInfo()
    {
        foreach (explode(',', App::$commonConfig->getMetadataBrokerList()) as $hostPorts) {
            [$host, $port] = explode(':', $hostPorts);

            // Get metadata information...
            $metadataRequest = new MetadataRequest();
            $topics = App::$commonConfig->getTopicNames();
            $topicNames = array_map(function ($item) {
                return String16::value($item);
            }, explode(',', $topics));
            $metadataRequest->setTopicName($topicNames);
            $data = $metadataRequest->pack();

            // Initiate socket request for data transfer...
            $socket = new Socket();
            try {
                $socket->connect($host, $port)->send($data);
            } catch (NormalSocketConnectException $e) {
                continue;
            }
            $socket->revcByKafka($metadataRequest);
            $socket->close();

            /** @var MetadataResponse $response */
            $response = $metadataRequest->response;
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

        if (count(Kafka::getInstance()->getBrokers()) === 0) {
            throw new \Exception(sprintf('MetadataBrokerList can\'t connect: %s',
                App::$commonConfig->getMetadataBrokerList()));
        }

        return self::getInstance();
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

    /**
     * @return MetadataManager
     */
    public function registerConfig(): self
    {
        App::$commonConfig = CommonConfig::getInstance();
//        App::$consumerConfig = new ConsumerConfig();
//        App::$producerConfig = new ProducerConfig();

        return self::getInstance();
    }
}