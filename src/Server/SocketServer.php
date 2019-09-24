<?php
declare(strict_types=1);

namespace Kafka\Server;

use Kafka\Enum\ProtocolEnum;
use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Protocol\Type\Int32;
use Swoole\Client;

class SocketServer
{
    /**
     * @var SocketServer $instance
     */
    private static $instance;

    /**
     * @var Client $client
     */
    private $client;

    private $needNewClient;

    private $defaultCloseClient;

    private function __construct()
    {
    }

    /**
     * @param bool $isNeedClient
     * @param bool $defaultCloseClient
     *
     * @return SocketServer
     */
    public static function getInstance(bool $isNeedClient = true, bool $defaultCloseClient = true)
    {
        if (!self::$instance instanceof SocketServer) {
            self::$instance = new self();
        }

        self::$instance->setNeedNewClient($isNeedClient);
        self::$instance->setDefaultCloseClient($defaultCloseClient);

        return self::$instance;
    }

    /**
     * @param string   $host
     * @param int      $port
     * @param callable $sendFn
     * @param callable $recvFn
     * @param float    $timeout
     * @param callable $closeFn
     *
     * @return array|bool
     */
    public function run(string $host, int $port, callable $sendFn, callable $recvFn,
                        $timeout = 3.0, callable $closeFn = null)
    {
        if (empty($closeFn)) {
            $closeFn = [$this, 'onCloseClient'];
        }

        if ($this->needNewClient) {
            $this->client = new Client(SWOOLE_SOCK_TCP);
            $retval = $this->client->connect($host, $port, $timeout);
            if (!$retval) {
                return false;
            }
        }
        $result = '';
        $payload = $sendFn();
        $length = $this->client->send($payload);
        $data = $this->recv(ProtocolTypeEnum::B32);
        $result = $recvFn($data, $this->client);
        call_user_func($closeFn, $this->client);

        return [true, $result];
    }

    public function setNeedNewClient(bool $isNeed = true)
    {
        $this->needNewClient = $isNeed;
    }

    public function setDefaultCloseClient(bool $isClose = true)
    {
        $this->defaultCloseClient = $isClose;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param int  $size
     * @param bool $closeClient
     *
     * @return string
     */
    private function recv(int $size, bool $closeClient = false): string
    {
        $data = $this->client->recv($size);

        if ($closeClient) {
            $this->onCloseClient($this->client);
        }

        return $data;
    }

    /**
     * @param Client $client
     */
    private function onCloseClient(Client $client)
    {
        if ($client->isConnected() && $this->defaultCloseClient) {
            $client->close();
        }
    }
}