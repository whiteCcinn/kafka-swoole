<?php

namespace Kafka\Api;

use App\App;
use Kafka\ClientKafka;
use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Exception\RequestException\OffsetCommitRequestException;
use Kafka\Exception\Socket\NormalSocketConnectException;
use Kafka\Kafka;
use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Request\OffsetCommit\PartitionsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommit\TopicsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommitRequest;
use Kafka\Protocol\Response\Metadata\PartitionMetadata;
use Kafka\Protocol\Response\Metadata\TopicMetadata;
use Kafka\Protocol\Response\MetadataResponse;
use Kafka\Protocol\Response\OffsetCommitResponse;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Socket\Socket;
use Kafka\Support\SingletonTrait;

/**
 * Class MetadataApi
 *
 * @package Kafka\Api
 */
class MetadataApi extends AbstractApi
{
    /**
     * @param string $brokerList
     * @param string $topics
     *
     * @return array
     */
    public function requestMetadata(string $brokerList, string $topics): array
    {
        foreach (explode(',', $brokerList) as $hostPorts) {
            [$host, $port] = explode(':', $hostPorts);

            // Get metadata information...
            $metadataRequest = new MetadataRequest();
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
            $partitions = [];
            $topicsPartitionLeader = $leaderTopicPartition = [];
            /** @var TopicMetadata $topicMetadata */
            foreach ($response->getTopics() as $topicMetadata) {
                /** @var PartitionMetadata $partitionMetadata */
                foreach ($topicMetadata->getPartitions() as $partitionMetadata) {
                    $partitions[$topicMetadata->getName()->getValue()][] = $partitionMetadata->getPartitionIndex()
                                                                                             ->getValue();
                    $topicsPartitionLeader[$topicMetadata->getName()
                                                         ->getValue()][$partitionMetadata->getPartitionIndex()
                                                                                         ->getValue()] = $partitionMetadata->getLeaderId()
                                                                                                                           ->getValue();
                    $leaderTopicPartition[$partitionMetadata->getLeaderId()->getValue()][$topicMetadata->getName()
                                                                                                       ->getValue()][] = $partitionMetadata->getPartitionIndex()
                                                                                                                                           ->getValue();
                }
            }

            return [
                'partitions'           => $partitions,
                'topicPartitionLeader' => $topicsPartitionLeader,
                'leaderTopicPartition' => $leaderTopicPartition
            ];
        }

        return [];
    }
}