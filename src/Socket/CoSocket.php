<?php

namespace Kafka\Socket;

use Co\Socket;

class CoSocket
{
    /**
     * @var Socket $swSocket
     */
    private $swSocket;

    /**
     * CoSocket constructor.
     *
     * @param int $domain   AF_INET、AF_INET6、AF_UNIX
     * @param int $type     SOCK_STREAM、SOCK_DGRAM、SOCK_RAW
     * @param int $protocol IPPROTO_TCP、IPPROTO_UDP、IPPROTO_STCP、IPPROTO_TIPC
     */
    public function createSocket(int $domain, int $type, int $protocol)
    {
        $this->swSocket = new Socket($domain, $type, $protocol);
    }

    /**
     * @see https://wiki.swoole.com/wiki/page/1083.html
     *
     * @param int $level
     * @param int $optName
     *
     * @return mixed
     */
    public function getOption(int $level, int $optName)
    {
        return $this->swSocket->getOption($level, $optName);
    }

    /**
     * @param int $level
     * @param int $optName
     * @param     $optVal
     *
     * @return mixed
     */
    public function setOption(int $level, int $optName, $optVal)
    {
        return $this->swSocket->setOption($level, $optName, $optVal);
    }

    /**
     * @param string $address
     * @param int    $port
     *
     * @return bool
     */
    public function bind(string $address, int $port = 0): bool
    {
        return $this->swSocket->bind($address, $port);
    }

    /**
     * @param int $backlog
     *
     * @return bool
     */
    public function listen(int $backlog = 0): bool
    {
        return $this->swSocket->listen($backlog);
    }

    /**
     * @param float $timeout
     *
     * @return mixed
     */
    public function accept(float $timeout = -1)
    {
        return $this->swSocket->accept($timeout);
    }

    /**
     * @param string $host
     * @param int    $port
     * @param float  $timeout
     *
     * @return mixed
     */
    public function connect(string $host, int $port = 0, float $timeout = -1): bool
    {
        $this->createSocket(AF_INET, SOCK_STREAM, 0);

        return $this->swSocket->connect($host, $port, $timeout);
    }

    /**
     * @param string $data
     * @param float  $timeout
     *
     * @return int | bool
     */
    public function send(string $data, float $timeout = -1)
    {
        return $this->swSocket->send($data, $timeout);
    }

    /**
     * @param string $data
     * @param float  $timeout
     *
     * @return int | bool
     */
    public function sendAll(string $data, float $timeout = -1)
    {
        return $this->swSocket->sendAll($data, $timeout);
    }

    /**
     * @param int   $length
     * @param float $timeout
     *
     * @return string | bool
     */
    public function recv(int $length = 65535, float $timeout = -1)
    {
        return $this->swSocket->recv($length, $timeout);
    }


    /**
     * @param int   $length
     * @param float $timeout
     *
     * @return string | bool
     */
    public function revcAll(int $length = 65535, float $timeout = -1)
    {
        return $this->swSocket->recvAll($length, $timeout);
    }

    /**
     * @param string $address
     * @param int    $port
     * @param string $data
     *
     * @return int | bool
     */
    public function sendto(string $address, int $port, string $data)
    {
        return $this->swSocket->sendto($address, $port, $data);
    }

    /**
     * @param array $peer
     * @param float $timeout
     *
     * @return string | bool
     */
    public function recvfrom(array &$peer, float $timeout = -1)
    {
        return $this->swSocket->recvfrom($peer, $timeout);
    }

    /**
     * @return mixed
     */
    public function getsocketname()
    {
        return $this->swSocket->getsockname();
    }

    /**
     * @return mixed
     */
    public function getpeername()
    {
        return $this->swSocket->getpeername();
    }

    /**
     * @return mixed
     */
    public function close()
    {
        return $this->swSocket->close();
    }

    /**
     * @return Socket
     */
    public function getRawSocket()
    {
        return $this->swSocket;
    }

    public function __destruct()
    {
        $this->close();
    }
}