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
}