<?php
declare(strict_types=1);

namespace Kafka\Storage;

use Kafka\Pool\RedisPool;
use Kafka\Support\SingletonTrait;
use Swoole\Coroutine\Redis;

/**
 * Class RedisStorage
 *
 * @package Kafka\Storage
 */
class RedisStorage
{
    use SingletonTrait;

    /**
     * @var int $configIndex
     */
    private $configIndex;

    /**
     * @var string $key
     */
    private $key;

    /**
     * @param array $data
     *
     * @return RedisStorage
     * @throws \Exception
     */
    public function push(array $data = []): self
    {
        if ($this->configIndex === null) {
            if (preg_match('/[\s\S]*(?P<index>\d+)$/', env('KAFKA_STORAGE_REDIS'), $matches)) {
                $this->configIndex = (int)$matches['index'];
            }
            $this->key = env('KAFKA_STORAGE_REDIS_KEY');
        }
        /** @var Redis $redis */
        ['handler' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);
        foreach ($data as $item) {
            $redis->rPush($this->key, json_encode($item));
        }

        RedisPool::getInstance($this->configIndex)->put($redis, $this->configIndex);

        return $this;
    }

    /**
     * @param int $number
     *
     * @return array
     * @throws \Exception
     */
    public function pop(int $number = 1): array
    {
        if ($this->configIndex === null) {
            if (preg_match('/[\s\S]*(?P<index>\d+)$/', env('KAFKA_STORAGE_REDIS'), $matches)) {
                $this->configIndex = (int)$matches['index'];
            }
            $this->key = env('KAFKA_STORAGE_REDIS_KEY');
        }
        /** @var Redis $redis */
        ['handler' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);
        $messages = [];

        while (count($messages) < $number) {
            $data = $redis->blPop($this->key, 3);
            if (!empty($data) && is_array($data)) {
                [, $message] = $data;
                $messages[] = json_decode($message, true);
            }
            \co::sleep(1);
        }
        RedisPool::getInstance($this->configIndex)->put($redis, $this->configIndex);

        return $messages;
    }
}