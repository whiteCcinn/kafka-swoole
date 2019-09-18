<?php
declare(strict_types=1);

namespace Kafka\Server;

use Swoole\Server;

class KafkaCServer
{
    /**
     * @var KafkaCServer $instance
     */
    private static $instance;

    /**
     * @var Server $server
     */
    private $server;

    /**
     * @var array $callBackFunc
     */
    private $callBackFunc = [];

    /**
     * KafkaCServer constructor.
     */
    private function __construct()
    {
        if (!$this->server instanceof Server) {
            $this->server = new Server(env('SERVER_IP'), env('SERVER_PORT'), SWOOLE_PROCESS, SWOOLE_TCP);
            swoole_set_process_name($this->getMasterName());
            $this->callBackFunc = [
                'ManagerStart' => [$this, 'onManagerStart'],
                'WorkerStart'  => [$this, 'onWorkerStart']
            ];
            $this->server->set([
                'reactor_num' => env('SERVER_REACTOR_NUM', 1),
                'worker_num'  => env('SERVER_WORKER_NUM', 1),
                'max_request' => env('SERVER_MAX_REQUEST', 50),
            ]);
            foreach ($this->callBackFunc as $name => $fn) {
                $this->server->on($name, $fn);
            }
        }
    }

    /**
     * @return KafkaCServer
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof KafkaCServer) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function start()
    {
        $this->getServer()->start();
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    public function onManagerStart()
    {
        swoole_set_process_name($this->getManagerName());
    }

    /**
     * @param Server $server
     * @param int    $workerId
     */
    public function onWorkerStart(Server $server, int $workerId)
    {
        if ($workerId >= $server->setting['worker_num']) {
            swoole_set_process_name($this->getTaskerName($workerId));
        } else {
            swoole_set_process_name($this->getWorkerName($workerId));
        }
    }

    /**
     * @return string
     */
    private function getMasterName(): string
    {
        return env('APP_NAME') . ':master';
    }

    /**
     * @return string
     */
    private function getManagerName(): string
    {
        return env('APP_NAME') . ':manager';
    }

    /**
     * @param int $workerId
     *
     * @return string
     */
    private function getWorkerName(int $workerId): string
    {
        return env('APP_NAME') . ":worker:{$workerId}";
    }

    /**
     * @param int $workerId
     *
     * @return string
     */
    private function getTaskerName(int $workerId): string
    {
        return env('APP_NAME') . ":tasker:{$workerId}";
    }
}