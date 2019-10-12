<?php

namespace Kafka\Api;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Kafka;
use Kafka\Protocol\Request\Produce\DataProduce;
use Kafka\Protocol\Request\Produce\MessageProduce;
use Kafka\Protocol\Request\Produce\MessageSetProduce;
use Kafka\Protocol\Request\Produce\TopicDataProduce;
use Kafka\Protocol\Request\ProduceRequest;
use Kafka\Protocol\Response\ProduceResponse;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;

class ProducerApi extends AbstractApi
{
    /**
     * @var array
     */
    private $connBrokerListMap = [];

    /**
     * ['conn'=>['topic' => ['partition'=>'leaderId']]]
     *
     * @var array
     */
    private $metadata = [];

    /**
     * @param string $conn
     * @param string $topics
     *
     * @return bool
     */
    private function refreshMetadata(string $conn, string $topics): bool
    {
        $topics = array_unique(array_merge(explode(',', $topics),
            isset($this->metadata[$conn]) ? (array_keys($this->metadata[$conn]) ?? []) : []));
        $topics = implode(',', $topics);

        $result = MetadataApi::getInstance()->requestMetadata($this->connBrokerListMap[$conn], $topics);
        if (empty($result)) {
            return false;
        }

        [
            'partitions'           => $topicPartitions,
            'topicPartitionLeader' => $topicsPartitionLeader,
        ] = $result;

        foreach ($topicPartitions as $topic => $partitions) {
            foreach ($partitions as $partition) {
                $leaderId = $topicsPartitionLeader[$topic][$partition];
                $this->metadata[$conn]['topicPartitionLeader'][$topic][$partition] = $leaderId;
            }
        }

        return true;
    }

    /**
     * @param string $conn
     * @param string $brokerList
     */
    public function setBrokerListMap(string $conn, string $brokerList)
    {
        $this->connBrokerListMap[$conn] = $brokerList;
    }

    /**
     * @param string      $conn
     * @param string      $topic
     * @param int|null    $partition
     * @param null|string $key
     * @param string      $message
     *
     * @return bool
     */
    public function produce(string $conn, string $topic, ?int $partition, ?string $key, string $message): bool
    {
        if (!isset($this->connBrokerListMap[$conn])) {
            return false;
        }

        if (!isset($this->metadata[$conn]['topicPartitionLeader'][$topic][$partition])) {
            self::refreshMetadata($conn, $topic);
        }

        Send:
        $partitions = array_keys($this->metadata[$conn]['topicPartitionLeader'][$topic]);
        $topicPartitionLeaders = $this->metadata[$conn]['topicPartitionLeader'];
        $topicPartition = isset($partitions[$topic]) ? $partitions[$topic] : [0];
        $topicPartitionLeader = isset($topicPartitionLeaders[$topic]) ? $topicPartitionLeaders[$topic] : current($topicPartitionLeaders);
        // Range
        if ($partition === null && $key === null) {
            shuffle($topicPartition);
            $assignPartition = current($topicPartition);
        } elseif ($partition === null && $key !== null) {
            $assignPartition = crc32(md5($key)) % count($topicPartition);
        } else {
            // if ($partition !== null && $key !== null) || ($partition !== null && $key === null)
            $assignPartition = (int)$partition;
        }

        $protocol = new ProduceRequest();
        $protocol->setAcks(Int16::value(1))
                 ->setTimeout(Int32::value(1 * 1000))
                 ->setTopicData([
                     (new TopicDataProduce())->setTopic(String16::value($topic))
                                             ->setData([
                                                 (new DataProduce())->setPartition(Int32::value($assignPartition))
                                                                    ->setMessageSet([
                                                                        (new MessageSetProduce())->setOffset(Int64::value(0))
                                                                                                 ->setMessage(
                                                                                                     (new MessageProduce())->setValue(Bytes32::value($message))
                                                                                                 ),
                                                                    ])
                                             ])
                 ]);
        $data = $protocol->pack();
        $socket = Kafka::getInstance()->getSocketByNodeId($topicPartitionLeader[$assignPartition]);
        $socket->send($data);
        $socket->revcByKafka($protocol);
        /** @var ProduceResponse $responses */
        $responses = $protocol->response;
        foreach ($responses->getResponses() as $response) {
            $info = $response->getPartitionResponses()[0];
            if (
                in_array($info->getErrorCode()->getValue(),
                    [ProtocolErrorEnum::UNKNOWN_TOPIC_OR_PARTITION, ProtocolErrorEnum::NOT_LEADER_FOR_PARTITION])
                &&
                $info->getBaseOffset()->getValue() === -1
            ) {
                goto Send;
            }
        }

        return true;
    }
}