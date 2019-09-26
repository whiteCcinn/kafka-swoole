<?php
declare(strict_types=1);

namespace Kafka\Support;

use Kafka\ClientKafka;
use Kafka\Config\CommonConfig;
use Kafka\Kafka;
use Kafka\Manager\MetadataManager;

trait SingletonTrait
{
    /**
     * @var MetadataManager | CommonConfig  $instance
     */
    protected static $instance;

    /**
     * Need to be compatible php 7.1.x, so this scene cannot be specified return type `object`
     * @return MetadataManager | CommonConfig | Kafka | ClientKafka
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
    }
}
