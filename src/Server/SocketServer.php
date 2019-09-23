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

    private function __construct()
    {
    }

    /**
     * @return SocketServer
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof SocketServer) {
            self::$instance = new self();
        }

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

        $client = new Client(SWOOLE_SOCK_TCP);
        $this->client = $client;
        $result = '';
        $retval = $client->connect($host, $port, $timeout);
        if (!$retval) {
            return false;
        }
        $payload = $sendFn();
        $length = $client->send($payload);
        $data = $this->recv(ProtocolTypeEnum::B32);
        $result = $recvFn($data, $client);
        call_user_func($closeFn, $client);

        return [true, $result];
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
        if ($client->isConnected()) {
            $client->close();
        }
    }
}