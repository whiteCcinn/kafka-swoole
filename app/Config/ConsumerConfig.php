<?php
declare(strict_types=1);

namespace App\Config;

use App\Exception\InvalidConfigException;
use App\Exception\InvalidConfigurationException;

/**
 * Class ConsumerConfig
 *
 * @package App\Config
 */
class ConsumerConfig extends AbstractConfig
{
    /** @var int $refreshMetadataBackoffMs */
    private $refreshMetadataBackoffMs;

    /** @var int $socketTimeout */
    private $socketTimeout;

    /** @var int $socketBufferSize */
    private $socketBufferSize;

    /** @var int $fetchSize */
    private $fetchSize;

    /** @var int $maxFetchSize */
    private $maxFetchSize;

    /** @var int $numConsumerFetchers */
    private $numConsumerFetchers;

    /** @var bool $autoCommitEnable */
    private $autoCommitEnable;

    /** @var int $autoCommitIntervalMs */
    private $autoCommitIntervalMs;

    /** @var int $queuedMaxMessages */
    private $queuedMaxMessages;

    /** @var int $rebalanceMaxRetries */
    private $rebalanceMaxRetries;

    /** @var string $offsetsStorage */
    private $offsetsStorage;

    /** @var string $autoOffsetReset */
    private $autoOffsetReset;

    /** @var string $clientId */
    private $clientId;

    /** @var string $groupId */
    private $groupId;

    /** @var string $partitionAssignmentStrategy */
    private $partitionAssignmentStrategy;

    /**
     * @param ConsumerConfig $config
     *
     * @return mixed|void
     * @throws InvalidConfigException
     * @throws InvalidConfigurationException
     */
    public function validate($config): void
    {
        $this->validateClientId($config->clientId);
        $this->validateGroupId($config->groupId);
        $this->validateAutoOffsetReset($config->autoOffsetReset);
        $this->validateOffsetsStorage($config->offsetsStorage);
        $this->validatePartitionAssignmentStrategy($config->partitionAssignmentStrategy);
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
     * @param string $groupId
     *
     * @throws \App\Exception\InvalidConfigurationException
     */
    public function validateGroupId(string $groupId): void
    {
        $this->validateChars('group.id', $groupId);
    }

    /**
     * @param string $autoOffsetReset
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function validateAutoOffsetReset(string $autoOffsetReset): bool
    {
        switch ($autoOffsetReset) {
            case 'smallest':
            case 'largest':
                return true;
            default:
                throw new InvalidConfigException("Wrong value " . $autoOffsetReset . " of auto.offset.reset in ConsumerConfig; " .
                    "Valid values are 'smallest' and 'largest'");
        }
    }

    /**
     * @param string $storage
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function validateOffsetsStorage(string $storage): bool
    {
        switch ($storage) {
            case 'zookeeper':
            case 'kafka':
                return true;
            default:
                throw new InvalidConfigException("Wrong value " . $storage . " of offsets.storage in consumer config; " .
                    "Valid values are 'zookeeper' and 'kafka'");
        }
    }

    /**
     * @param string $strategy
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function validatePartitionAssignmentStrategy(string $strategy): bool
    {
        switch ($strategy) {
            case 'range':
            case 'roundrobin':
                return true;
            default:
                throw new InvalidConfigException("Wrong value " . $strategy . " of partition.assignment.strategy in consumer config; " .
                    "Valid values are 'range' and 'roundrobin'");
        }
    }
}