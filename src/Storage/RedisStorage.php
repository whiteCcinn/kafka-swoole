<?php
declare(strict_types=1);

namespace Kafka\Storage;

use Kafka\Pool\RedisPool;
use Kafka\Support\SingletonTrait;
use SebastianBergmann\CodeCoverage\Report\PHP;
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
     * @var string $pendingKey
     */
    private $pendingKey;

    /**
     * @var string $processingKey
     */
    private $processingKey;

    private function init()
    {
        if ($this->configIndex === null) {
            if (preg_match('/[\s\S]*(?P<index>\d+)$/', env('KAFKA_STORAGE_REDIS'), $matches)) {
                $this->configIndex = (int)$matches['index'];
            }
            $this->pendingKey = env('KAFKA_STORAGE_REDIS_PENDING_KEY');
            $this->processingKey = env('KAFKA_STORAGE_REDIS_PROCESSING_KEY');
        }
    }

    /**
     * @param array $data
     *
     * @return RedisStorage
     * @throws \Exception
     */
    public function push(array $data = []): self
    {
        $this->init();
        /** @var Redis $redis */
        ['handler' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);
        foreach ($data as $item) {
            $info = [
                'time'    => time(),
                'message' => $item
            ];
            $redis->lPush($this->pendingKey, json_encode($info));
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
        $this->init();
        /** @var Redis $redis */
        ['handler' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);
        $messages = [];

        while (count($messages) < $number) {
            $data = $redis->rpoplpush($this->pendingKey, $this->processingKey);
            if (!empty($data)) {
                $messages[] = json_decode($data, true);
            }
//            \co::sleep(1);
        }
        RedisPool::getInstance($this->configIndex)->put($redis, $this->configIndex);

        return $messages;
    }

    /**
     * @param array $info
     *
     * @throws \Exception
     */
    public function ack(array $info)
    {
        $this->init();
        /** @var Redis $redis */
        ['handler' => $redis] = RedisPool::getInstance($this->configIndex)->get($this->configIndex);

        var_dump($redis->lRem($this->processingKey, json_encode($info), 1));

        RedisPool::getInstance($this->configIndex)->put($redis, $this->configIndex);
    }
}