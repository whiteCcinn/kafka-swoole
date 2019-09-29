<?php
declare(strict_types=1);

namespace Kafka\Config;

use Kafka\Enum\OffsetResetEnum;
use Kafka\Enum\ProtocolPartitionAssignmentStrategyEnum;
use Kafka\Exception\InvalidConfigException;
use Kafka\Support\Str;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CommonConfig
 *
 * @package Kafka\Config
 */
class CommonConfig extends AbstractConfig
{
    /**
     * @var string $metadataBrokerList
     */
    protected $metadataBrokerList;

    /**
     * @var int $topicMetadataRefreshIntervalMs
     */
    protected $topicMetadataRefreshIntervalMs = 600000;

    /**
     * @var string $groupId
     */
    protected $groupId;

    /**
     * @var string $topicNames
     */
    protected $topicNames;

    /**
     * @var int $heartbeatIntervalMs
     */
    protected $heartbeatIntervalMs;

    /**
     * @var int $groupKeepSessionMaxMs
     */
    protected $groupKeepSessionMaxMs;

    /**
     * @var string $partitionAssignmentStrategy
     */
    protected $partitionAssignmentStrategy;

    /**
     * @var int $autoCommitIntervalMs
     */
    protected $autoCommitIntervalMs;

    /**
     * @var string $autoOffsetReset
     */
    protected $autoOffsetReset;

    /**
     * CommonConfig constructor.
     */
    protected function __construct()
    {
        $this->loadConfig();
        parent::__construct();
    }

    private function loadConfig()
    {
        $values = Yaml::parseFile(KAFKA_SWOOLE_CONFIG_COMMON_FILE);
        foreach ($values as $var => $value) {
            $var = Str::camel($var);
            // Expression assignment is supported
            if (preg_match('/^\s*-?\d+\s*[\+\-\*\/%]\s*-?\d+\s*([\+\-\*\/\%]\s*-?\d+\s*)*$/', $value)) {
                eval('$value = ' . $value . ';');
            }
            $this->{$var} = $value;
        }
    }

    /**
     * @param CommonConfig $config
     *
     * @return mixed|void
     * @throws InvalidConfigException
     */
    public function validate($config)
    {
        $this->validateMetadataBrokerList($config->metadataBrokerList);
        $this->validateHeartbeatAndGroupKeepTime(
            $config->getHeartbeatIntervalMs(),
            $config->getGroupKeepSessionMaxMs()
        );
        $this->validatePartitionAssignmentStrategy($config->getPartitionAssignmentStrategy());
        $this->validateAutoCommitIntervalMs($config->getAutoCommitIntervalMs());
        $this->validateAutoOffsetReset($config->getAutoOffsetReset());
    }

    /**
     * @param string $metadataBrokerList
     *
     * @return bool
     * @throws InvalidConfigException
     */
    private function validateMetadataBrokerList(string $metadataBrokerList): bool
    {
        if (!empty($metadataBrokerList)) {
            $hostPorts = explode(',', $metadataBrokerList);
            if (!empty($hostPorts) && is_array($hostPorts)) {
                foreach ($hostPorts as $hostPort) {
                    [$host, $port] = explode(':', $hostPort);
                    if (!preg_match('/^http\/\/.*/', $host) && preg_match('/[0-9]+/', $port)) {
                        return true;
                    }
                }
            }
        }

        throw new InvalidConfigException(trans("config.validate.MetadataBrokerList", [$metadataBrokerList]));
    }

    /**
     * @param int $heartbeatIntervalMs
     * @param int $groupKeepSessionMaxMs
     *
     * @throws InvalidConfigException
     */
    private function validateHeartbeatAndGroupKeepTime(int $heartbeatIntervalMs, int $groupKeepSessionMaxMs)
    {
        if ($heartbeatIntervalMs > $groupKeepSessionMaxMs)
            throw new InvalidConfigException("heartbeat.interval.ms = " . $heartbeatIntervalMs . " can't be larger than group.keep.session.max.ms size = " . $groupKeepSessionMaxMs);
    }

    /**
     * @param string $strategy
     *
     * @throws InvalidConfigException
     */
    private function validatePartitionAssignmentStrategy(string $strategy)
    {
        if (!in_array($strategy, ProtocolPartitionAssignmentStrategyEnum::getAllText())) {
            throw new InvalidConfigException('partition.assignment.strategy Illegal value：' . $strategy . ' Please See ProtocolPartitionAssignmentStrategyEnum::class');
        }
    }

    /**
     * @param int $autoCommitIntervalMs
     *
     * @throws InvalidConfigException
     */
    private function validateAutoCommitIntervalMs(int $autoCommitIntervalMs)
    {
        if ($autoCommitIntervalMs <= 0) {
            throw new InvalidConfigException('auto.commit.interval.ms must be greater than 0');
        }
    }

    /**
     * @param string $autoOffsetReset
     *
     * @throws InvalidConfigException
     */
    private function validateAutoOffsetReset(string $autoOffsetReset)
    {
        if (!in_array($autoOffsetReset, OffsetResetEnum::getAllText())) {
            throw new InvalidConfigException('auto.offset.reset Illegal value：' . $autoOffsetReset . ' Please See OffsetResetEnum::class');
        }
    }

    /**
     * @return mixed
     */
    public function getMetadataBrokerList()
    {
        return $this->metadataBrokerList;
    }

    /**
     * @return int
     */
    public function getTopicMetadataRefreshIntervalMs(): int
    {
        return $this->topicMetadataRefreshIntervalMs;
    }

    /**
     * @return string
     */
    public function getTopicNames(): string
    {
        return $this->topicNames;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @return int
     */
    public function getHeartbeatIntervalMs(): int
    {
        return $this->heartbeatIntervalMs;
    }

    /**
     * @return int
     */
    public function getGroupKeepSessionMaxMs(): int
    {
        return $this->groupKeepSessionMaxMs;
    }

    /**
     * @return string
     */
    public function getPartitionAssignmentStrategy(): string
    {
        return $this->partitionAssignmentStrategy;
    }

    /**
     * @return int
     */
    public function getAutoCommitIntervalMs()
    {
        return $this->autoCommitIntervalMs;
    }

    /**
     * @return string
     */
    public function getAutoOffsetReset(): string
    {
        return $this->autoOffsetReset;
    }
}