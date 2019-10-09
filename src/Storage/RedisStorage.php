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
        ['redis' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);
        foreach ($data as $item) {
            $redis->rPush($this->key, json_encode($item));
        }

        RedisPool::getInstance($this->configIndex)->put($redis, $this->configIndex);
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
        ['redis' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);
        $message = [];

        while (count($message) < $number) {
            $data = $redis->blPop($this->key, 3);
            if (!empty($data)) {
                $message[] = json_decode($data, true);
            }
        }
        RedisPool::getInstance($this->configIndex)->put($redis, $this->configIndex);

        return $message;
    }
}