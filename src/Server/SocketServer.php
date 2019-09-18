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
     * @var
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
     * @return mixed
     */
    public function getServer()
    {
        return $this->socket;
    }
}