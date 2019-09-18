<?php
declare(strict_types=1);

namespace Kafka\Config;

use App\Exception\InvalidConfigException;
use App\Exception\InvalidConfigurationException;

/**
 * Class ProducerConfig
 *
 * @package App\Config
 */
class ProducerConfig extends CommonConfig
{
    protected const FILE = 'producer.yaml';

    /** @var string $clientId */
    private $clientId;

    /** @var int $batchNumMessages */
    private $batchNumMessages;

    /** @var int $queueBufferingMaxMessages */
    private $queueBufferingMaxMessages;

    /** @var string $producerType */
    private $producerType;

    /**
     * @param ProducerConfig $config
     *
     * @return mixed|void
     * @throws InvalidConfigException
     * @throws InvalidConfigurationException
     */
    public function validate($config): void
    {
        $this->validateClientId($config->clientId);
        $this->validateBatchSize($config->batchNumMessages, $config->queueBufferingMaxMessages);
        $this->validateProducerType($config->producerType);
    }

    /**
     * @param string $clientId
     *
     * @throws \App\Exception\InvalidConfigurationException
     */
    public function validateClientId(string $clientId): void
    {
        $this->validateChars('client.id', $clientId);
    }

    /**
     * @param int $batchSize
     * @param int $queueSize
     *
     * @throws InvalidConfigException
     */
    public function validateBatchSize(int $batchSize, int $queueSize): void
    {
        if ($batchSize > $queueSize)
            throw new InvalidConfigException("Batch size = " . $batchSize . " can't be larger than queue size = " . $queueSize);
    }

    /**
     * @param string $producerType
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function validateProducerType(string $producerType): bool
    {
        switch ($producerType) {
            case 'sync':
            case 'async':
                return true;
            default:
                throw new InvalidConfigException("Invalid value " . $producerType . " for producer.type, valid values are sync/async");
        }
    }
}