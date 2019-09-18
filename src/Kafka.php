<?php

namespace Kafka;

class Kafka
{
    /**
     * @var Kafka $instance
     */
    private static $instance;

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
     */
    private $partitions;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return Kafka
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Kafka) {
            self::$instance = new self;
        }

        return self::$instance;
    }

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
}