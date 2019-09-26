<?php

namespace App;

use Kafka\Config\CommonConfig;
use Kafka\Config\ConsumerConfig;
use Kafka\Config\ProducerConfig;
use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Translation\Translator;

class App
{
    private static $once = false;

    /**
     * @var EventDispatcher $dispatcher
     */
    public static $dispatcher;

    /**
     * @var Application $application
     */
    public static $application;

    /**
     * @var Translator $translator
     */
    public static $translator;

    /**
     * @var CommonConfig $commonConfig
     */
    public static $commonConfig;

    /**
     * @var ConsumerConfig $consumerConfig
     */
    public static $consumerConfig;

    /**
     * @var ProducerConfig $producerConfig
     */
    public static $producerConfig;

    /**
     * @throws \Exception
     */
    public static function boot()
    {
        if (!self::$once) {
            self::changeOnce();
        }
    }

    private static function changeOnce(): void
    {
        self::$once = !self::$once;
    }

    /**
     * @return array
     */
    public static function getBroker()
    {
        return Kafka::getInstance()->getBrokers();
    }
}