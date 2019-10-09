<?php
declare(strict_types=1);

namespace Kafka\Server;

use App\Handler\HighLevelHandler;
use Kafka\Enum\ClientApiModeEnum;
use Kafka\Event\CoreLogicAfterEvent;
use Kafka\Event\CoreLogicBeforeEvent;
use Kafka\Event\CoreLogicEvent;
use Kafka\Event\SinkerEvent;
use Swoole\Process;
use Swoole\Server;
use \co;

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
     * @var int $masterPid
     */
    private $masterPid;

    /**
     * @var int $nextKafkaIndex
     */
    private $nextKafkaIndex = 0;

    /**
     * @var array $kafkaProcesses
     */
    private $kafkaProcesses = [];

    /**
     * @var int $nextSinkerIndex
     */
    private $nextSinkerIndex = 0;

    /**
     * @var array $sinkerProcesses
     */
    private $sinkerProcesses = [];

    /**
     * KafkaCServer constructor.
     */
    private function __construct()
    {
        if (!$this->server instanceof Server) {
            $this->server = new Server(env('SERVER_IP'), (int)env('SERVER_PORT'), SWOOLE_PROCESS, SWOOLE_TCP);
            swoole_set_process_name($this->getMasterName());
            $this->callBackFunc = [
                'ManagerStart' => [$this, 'onManagerStart'],
                'WorkerStart'  => [$this, 'onWorkerStart'],
                'Receive'      => [$this, 'onReceive']
            ];
            $this->server->set([
                'reactor_num' => env('SERVER_REACTOR_NUM', 1),
                'worker_num'  => env('SERVER_WORKER_NUM', 1),
                'max_request' => env('SERVER_MAX_REQUEST', 50),
            ]);
            foreach ($this->callBackFunc as $name => $fn) {
                $this->server->on($name, $fn);
            }
            $this->masterPid = posix_getpid();
        }
    }

    /**
     * @return KafkaCServer
     */
    public static function getInstance(): KafkaCServer
    {
        if (!self::$instance instanceof KafkaCServer) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function start(): void
    {
        $this->getServer()->start();
    }

    /**
     * @return Server
     */
    public function getServer(): Server
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
    public function onWorkerStart(Server $server, int $workerId): void
    {
        if ($workerId >= $server->setting['worker_num']) {
            swoole_set_process_name($this->getTaskerName($workerId));
        } else {
            swoole_set_process_name($this->getWorkerName($workerId));
        }
    }

    public function onReceive($serv, $fd, $reactor_id, $data)
    {
        //群发收到的消息
//            $process->write($data);
        var_dump('接收到消息');
        var_dump($data);
    }

    public function setSinkerProcess(int $processNum): KafkaCServer
    {
        for ($i = 0; $i < $processNum; $i++) {
            $this->createSinkerProcess();
        }

        return self::getInstance();
    }

    public function createSinkerProcess($index = null)
    {
        $process = new Process(function (Process $process) use (&$index) {
            if (is_null($index)) {
                $index = $this->nextSinkerIndex;
                $this->nextSinkerIndex++;
            }
            swoole_set_process_name($this->getProcessName('sinker'));

            // Receiving process messages
            swoole_event_add($process->pipe, function () use ($process) {
                $msg = $process->read();
                var_dump($msg);
            });

            // Sinker Logic
            go(function () {
                dispatch(new SinkerEvent(), SinkerEvent::NAME);
            });
        }, false, 1, true);

        $pid = $process->start();
        $this->sinkerProcesses[$index] = $pid;

        return $pid;
    }

    public function setKafkaProcess(int $processNum): KafkaCServer
    {
        for ($i = 0; $i < $processNum; $i++) {
            $this->createKafkaProcess();
        }

        return self::getInstance();
    }

    public function createKafkaProcess($index = null)
    {
        $process = new Process(function (Process $process) use (&$index) {
            if (is_null($index)) {
                $index = $this->nextKafkaIndex;
                $this->nextKafkaIndex++;
            }
            swoole_set_process_name($this->getProcessName());

            // Receiving process messages
            swoole_event_add($process->pipe, function () use ($process) {
                $msg = $process->read();
                var_dump($msg);
            });

            // Heartbeat
            go(function () use ($process) {
                while (true) {
                    $this->checkMasterPid($process);
                    echo sprintf('pid:%d,Check if the service master process exists every %s seconds...' . PHP_EOL,
                        getmypid(), 60);
                    co::sleep(60);
                }
            });

            // Core Logic
            go(function () {
                dispatch(new CoreLogicBeforeEvent(), CoreLogicBeforeEvent::NAME);
                dispatch(new CoreLogicEvent(), CoreLogicEvent::NAME);
                dispatch(new CoreLogicAfterEvent(), CoreLogicAfterEvent::NAME);
            });
        }, false, 1, true);

        $pid = $process->start();
        $this->kafkaProcesses[$index] = $pid;

        return $pid;
    }

    public function checkMasterPid(Process $process)
    {
        if (!Process::kill($this->masterPid, 0)) {
            $process->exit();
        }
    }

    /**
     * @param $ret
     *
     * @throws \Exception
     */
    public function rebootProcess($ret)
    {
        $pid = $ret['pid'];
        $index = array_search($pid, $this->kafkaProcesses);
        if ($index !== false) {
            $index = intval($index);
            $new_pid = $this->CreateProcess($index);
            echo "rebootProcess: {$index}={$new_pid} Done\n";

            return;
        }
        throw new \Exception('rebootProcess Error: no pid');
    }

    /**
     * @throws \Exception
     */
    public function processWait()
    {
        while (1) {
            if (count($this->kafkaProcesses)) {
                $ret = Process::wait();
                if ($ret) {
                    $this->rebootProcess($ret);
                }
            } else {
                break;
            }
        }
    }


    /**
     * @param string $type
     *
     * @return string
     */
    private function getProcessName($type = 'kafka'): string
    {
        return env('APP_NAME') . ':process' . ":{$type}";
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