<?php
declare(strict_types=1);

namespace Kafka;

use Kafka\Socket\Socket;
use Kafka\Support\SingletonTrait;
use \co;

class Kafka
{
    use SingletonTrait;

    /**
     * @var array $brokers
     */
    private $brokers;

    /**
     * @var array $topics
     */
    private $topics;

    /**
     * @var array $partitions
     * ['topicName'=>['partitionIndex']]
     */
    private $partitions;

    /**
     * @var array $topicsPartitionLeader
     * ['topicName'=>['partitionIndex' =>'leaderId']]
     */
    private $topicsPartitionLeader;

    /**
     * @var array $leaderTopicPartition
     * ['leaderId'=>['topicName' =>['partitionIndex']]]
     */
    private $leaderTopicPartition;

    /**
     * @return array
     */
    public function getBrokers(): array
    {
        return $this->brokers;
    }

    /**
     * @param array $brokers
     *
     * @return Kafka
     */
    public function setBrokers(array $brokers): Kafka
    {
        $this->brokers = $brokers;

        return $this;
    }

    /**
     * @return array
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param array $topics
     *
     * @return Kafka
     */
    public function setTopics(array $topics): Kafka
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @return array
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param array $partitions
     *
     * @return Kafka
     */
    public function setPartitions(array $partitions): Kafka
    {
        $this->partitions = $partitions;

        return $this;
    }

    /**
     * @param string $topic
     * @param int    $partition
     *
     * @return int
     */
    public function getLeaderByTopicPartition(string $topic, int $partition)
    {
        static $staticLeaderByTopicPartition;

        if (is_array($staticLeaderByTopicPartition) && isset($staticLeaderByTopicPartition[$topic][$partition])) {
            return $staticLeaderByTopicPartition[$topic][$partition];
        }
        foreach ($this->topicsPartitionLeader as $topicName => $partitionLeader) {
            if ($topic === $topicName) {
                foreach ($partitionLeader as $partitionIndex => $leader) {
                    if ($partitionIndex === $partition) {
                        $staticLeaderByTopicPartition[$topicName][$partitionIndex] = $leader;

                        return $leader;
                    }
                }
            }
        }

        return -1;
    }

    /**
     * @return array
     */
    public function getTopicsPartitionLeader(): array
    {
        return $this->topicsPartitionLeader;
    }

    /**
     * @param string $topic
     * @param int    $partition
     *
     * @return int
     */
    public function getTopicsPartitionLeaderByTopicAndPartition(string $topic, int $partition): int
    {
        return $this->topicsPartitionLeader[$topic][$partition];
    }

    /**
     * @param array $topicsPartitionLeader
     *
     * @return Kafka
     */
    public function setTopicsPartitionLeader(array $topicsPartitionLeader): Kafka
    {
        $this->topicsPartitionLeader = $topicsPartitionLeader;

        return $this;
    }

    /**
     * @return array
     */
    public function getLeaderTopicPartition(): array
    {
        return $this->leaderTopicPartition;
    }

    /**
     * @param array $leaderTopicPartition
     *
     * @return Kafka
     */
    public function setLeaderTopicPartition(array $leaderTopicPartition): Kafka
    {
        $this->leaderTopicPartition = $leaderTopicPartition;

        return $this;
    }

    /**
     * @return array
     */
    public function getRandBroker(): array
    {
        $array = $this->getBrokers();
        shuffle($array);

        return array_pop($array);
    }

    /**
     * @param int $nodeId
     *
     * @return array|mixed
     */
    public function getBrokerInfoByNodeId(int $nodeId)
    {
        static $staticBroker;

        if (is_array($staticBroker) && isset($staticBroker[$nodeId])) {
            return $staticBroker[$nodeId];
        }

        foreach ($this->brokers as $broker) {
            if ($broker['nodeId'] === $nodeId) {
                $staticBroker[$nodeId] = $broker;

                return $broker;
            }
        }

        return [];
    }

    public function getSocketByNodeId(int $nodeId)
    {
        static $staticSocket;

        $cid = co::getCid();
        if (is_array($staticSocket) && isset($staticSocket[$cid][$nodeId])) {
            return $staticSocket[$cid][$nodeId];
        }

        ['host' => $host, 'port' => $port] = $this->getBrokerInfoByNodeId($nodeId);
        $socket = new Socket();
        $socket->connect($host, $port);
        $staticSocket[$cid][$nodeId] = $socket;

        return $socket;
    }
}