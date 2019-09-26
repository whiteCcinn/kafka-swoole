<?php

namespace Kafka\Socket;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Protocol\AbstractRequest;
use Swoole\Coroutine;

/**
 * Class Socket
 *
 * @package Kafka\Socket
 */
class Socket
{
    /**
     * @var CoSocket | NormalSocket
     */
    private $socketInstance;

    /**
     * Socket constructor.
     */
    public function __construct()
    {
        if (Coroutine::getCid() === -1) {
            $socket = new NormalSocket();
        } else {
            $socket = new CoSocket();
        }
        $this->setSocketInstance($socket);
    }

    /**
     * @param $instance
     */
    public function setSocketInstance($instance)
    {
        $this->socketInstance = $instance;
    }

    /**
     * @param string $host
     * @param int    $port
     * @param int    $timeout
     *
     * @return $this
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    public function connect(string $host, int $port, $timeout = -1)
    {
        $this->socketInstance->connect($host, $port, $timeout);

        return $this;
    }

    /**
     * @param string $data
     * @param        $timeout
     *
     * @return bool|int
     */
    public function send($data, $timeout = -1)
    {
        return $this->socketInstance->send($data, $timeout);
    }

    /**
     * @param int $length
     * @param int $timeout
     *
     * @return bool|string
     * @throws \Kafka\Exception\Socket\NormalSocketException
     */
    public function recv(int $length = 65535, $timeout = -1)
    {
        return $this->socketInstance->recv($length, $timeout);
    }

    /**
     * @param AbstractRequest $requestProtocol
     * @param int             $timeout
     *
     * @return string
     */
    public function revcByKafka($requestProtocol, $timeout = -1): string
    {
        $binHeaderSize = $this->socketInstance->recv(ProtocolTypeEnum::B32, $timeout);
        $requestProtocol->response->unpack($binHeaderSize, $this);

        return $requestProtocol->response->getCompleteProtocol();
    }


    public function close()
    {
        $this->socketInstance->close();
    }

    /**
     * @return CoSocket|NormalSocket
     */
    public function getSocketInstance()
    {
        return $this->socketInstance;
    }

    public function __destruct()
    {
        $this->close();
    }
}