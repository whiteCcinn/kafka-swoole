<?php
declare(strict_types=1);

namespace Kafka\Pool;

use \Swoole\Coroutine\Channel;
use \Swoole\Coroutine\Redis;
use \RuntimeException;

/**
 * Class RedisPool
 *
 * @package Kafka\Pool
 */
class RedisPool
{

    /**
     * @var array Channel
     */
    protected $pool;

    /**
     * @var array $once
     */
    private $once;

    /**
     * @var array
     */
    protected static $instance;

    /**
     * @var int $number
     */
    private static $number;

    /**
     * RedisPool constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param int $index
     *
     * @return RedisPool
     * @throws \Exception
     */
    public static function getInstance(int $index = 0): self
    {
        if (self::$number === null) {
            self::$number = env('POOL_REDIS_NUM');
        }

        if ($index >= self::$number) {
            throw new \Exception('invalid index');
        }

        if (self::$instance[$index] === null) {
            static::$instance[$index] = new static();
            static::$instance[$index]->init($index);
        }

        return static::$instance[$index];
    }


    /**
     * @param int $index
     *
     * @return RedisPool
     */
    protected function init(int $index = 0): self
    {
        if (!isset($this->once[$index])) {
            $this->once[$index] = false;
        }
        if (!$this->once[$index]) {
            $size = (int)env("POOL_REDIS_{$index}_MAX_NUMBER");
            $maxIdle = (int)env("POOL_REDIS_{$index}_MAX_IDLE");
            $host = env("POOL_REDIS_{$index}_HOST");
            $port = (int)env("POOL_REDIS_{$index}_PORT");
            $auth = env("POOL_REDIS_{$index}_AUTH");
            $db = (int)env("POOL_REDIS_{$index}_DB");
            $this->pool[$index] = new Channel($size);
            for ($i = 0; $i < $maxIdle; $i++) {
                $redis = new Redis();
                $res = $redis->connect($host, $port);
                if ($res == false) {
                    throw new RuntimeException("failed to connect redis server.");
                } else {#
                    if ($auth !== null) {
                        $redis->auth($auth);
                    }
                    if ($db !== null) {
                        $redis->select($db);
                    }
                    $this->put($redis, $index);
                }
            }
        }

        return $this;
    }

    /**
     * @param     $redis
     * @param int $index
     *
     * @return RedisPool
     */
    function put($redis, int $index = 0): self
    {
        if (!isset($this->once[$index])) {
            throw new RuntimeException("The pool invalid");
        }
        $this->pool[$index]->push($redis);

        return $this;
    }

    /**
     * @param int $index
     *
     * @return array
     */
    function get(int $index = 0): array
    {
        if (!isset($this->once[$index])) {
            throw new RuntimeException("The pool invalid");
        }

        return ['index' => $index, 'handler' => $this->pool[$index]->pop()];
    }
}