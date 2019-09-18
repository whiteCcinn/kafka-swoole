<?php
declare(strict_types=1);

namespace Kafka\Server;

use Co\Socket;

class SocketServer
{
    /**
     * @var SocketServer $instance
     */
    private static $instance;

    /**
     * @var Socket $socket
     */
    private static $socket;

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
            self::$socket = new Socket(AF_INET, SOCK_STREAM, 0);
        }

        return self::$instance;
    }

    /**
     * @return Socket
     */
    public function getSocket()
    {
        return self::$socket;
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
        $result = '';
        $socket = self::getInstance()->getSocket();
        $retval = $socket->connect($host, $port, $timeout);
        if (!$retval) {
            return false;
        }
        $payload = $fn1();
        $length = $socket->send($payload);
        $data = $socket->recv();
        $result = $fn2($data);
        if (empty($data)) {
            $socket->close();
        }

        return $result;
    }
}