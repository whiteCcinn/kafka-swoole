<?php
declare(strict_types=1);

namespace Kafka\Config;

use App\Exception\InvalidConfigException;
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
     * @var string $topicNames
     */
    protected $topicNames;

    public function __construct()
    {
        $this->loadConfig();
        parent::__construct();
    }

    private function loadConfig()
    {
        $values = Yaml::parseFile(KAFKA_SWOOLE_CONFIG_COMMON_FILE);
        foreach ($values as $var => $value) {
            $var = Str::camel($var);
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
    }

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
}