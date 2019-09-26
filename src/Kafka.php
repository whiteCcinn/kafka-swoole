<?php

namespace Kafka;

use Kafka\Support\SingletonTrait;

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
     * @return array
     */
    public function getRandBroker(): array
    {
        $array = $this->getBrokers();
        shuffle($array);

        return array_pop($array);
    }
}