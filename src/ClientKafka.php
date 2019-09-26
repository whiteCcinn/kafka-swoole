<?php

namespace Kafka;

use Kafka\Socket\Socket;
use Kafka\Support\SingletonTrait;

/**
 * Class ClientKafka
 *
 * @package Kafka
 */
class ClientKafka
{
    use SingletonTrait;

    /**
     * @var array $offsetConnect
     */
    private $offsetConnect = [
        'nodeId' => '',
        'host'   => '',
        'port'   => '',
        'socket' => null
    ];

    /**
     * @param int $nodeId
     *
     * @return ClientKafka
     */
    public function setOffsetConnectWithNodeId(int $nodeId): self
    {
        $this->offsetConnect['nodeId'] = $nodeId;

        return self::getInstance();
    }

    /**
     * @param string $host
     *
     * @return ClientKafka
     */
    public function setOffsetConnectWithHost(string $host): self
    {
        $this->offsetConnect['host'] = $host;

        return self::getInstance();
    }

    /**
     * @param int $port
     *
     * @return ClientKafka
     */
    public function setOffsetConnectWithPort(int $port): self
    {
        $this->offsetConnect['port'] = $port;

        return self::getInstance();
    }

    /**
     * @param Socket $socket
     *
     * @return ClientKafka
     */
    public function setOffsetConnectWithHandler(Socket $socket): self
    {
        $this->offsetConnect['socket'] = $socket;

        return self::getInstance();
    }
}