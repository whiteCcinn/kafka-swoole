<?php
declare(strict_types=1);

namespace Kafka\Server;

use Swoole\Client;

class SocketServer
{
    /**
     * @var SocketServer $instance
     */
    private static $instance;

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
     * @param callable $fn1
     * @param callable $fn2
     * @param float    $timeout
     *
     * @return string
     */
    public function run(string $host, int $port, callable $fn1, callable $fn2, $timeout = 3.0)
    {
        $client = new Client(SWOOLE_SOCK_TCP);
        $result = '';
        $retval = $client->connect($host, $port, $timeout);
        if (!$retval) {
            return false;
        }
        $payload = $fn1();
        $length = $client->send($payload);
        $data = $client->recv();
        $result = $fn2($data);
        $client->close();

        return $result;
    }
}