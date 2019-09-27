<?php

namespace Kafka;

use Kafka\Protocol\Response\JoinGroup\MembersJoinGroup;
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
     * @var int $generationId
     */
    private $generationId;

    /**
     * @var string $protocolName
     */
    private $protocolName;

    /**
     * @var string $leader
     */
    private $leader;

    /**
     * @var string $memberId
     */
    private $memberId;

    /**
     * @var bool $isLeader
     */
    private $isLeader;

    /**
     * @var MembersJoinGroup[] $members
     */
    private $members = [];

    /**
     * @var array
     */
    private $topicMemberIds = [];

    /**
     * @var array
     */
    private $memberIdTopics = [];

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
    public function setOffsetConnectWithSocket(Socket $socket): self
    {
        $this->offsetConnect['socket'] = $socket;

        return self::getInstance();
    }

    /**
     * @return string
     */
    public function getOffsetConnectNodeId(): string
    {
        return self::getInstance()->offsetConnect['nodeId'];
    }

    /**
     * @return int
     */
    public function getOffsetConnectPort(): int
    {
        return self::getInstance()->offsetConnect['port'];
    }

    /**
     * @return string
     */
    public function getOffsetConnectHost(): string
    {
        return self::getInstance()->offsetConnect['host'];
    }

    /**
     * @return Socket
     */
    public function getOffsetConnectSocket()
    {
        return self::getInstance()->offsetConnect['socket'];
    }

    /**
     * @return int
     */
    public function getGenerationId(): int
    {
        return $this->generationId;
    }

    /**
     * @param int $generationId
     *
     * @return ClientKafka
     */
    public function setGenerationId(int $generationId): self
    {
        $this->generationId = $generationId;

        return self::getInstance();
    }

    /**
     * @return string
     */
    public function getProtocolName(): string
    {
        return $this->protocolName;
    }

    /**
     * @param string $protocolName
     *
     * @return ClientKafka
     */
    public function setProtocolName(string $protocolName): self
    {
        $this->protocolName = $protocolName;

        return self::getInstance();
    }

    /**
     * @return string
     */
    public function getLeader(): string
    {
        return $this->leader;
    }

    /**
     * @param string $leader
     *
     * @return ClientKafka
     */
    public function setLeader(string $leader): self
    {
        $this->leader = $leader;

        return self::getInstance();
    }

    /**
     * @return string
     */
    public function getMemberId(): string
    {
        return $this->memberId;
    }

    /**
     * @param string $memberId
     *
     * @return ClientKafka
     */
    public function setMemberId(string $memberId): self
    {
        $this->memberId = $memberId;

        return self::getInstance();
    }

    /**
     * @return MembersJoinGroup[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     *
     * @return ClientKafka
     */
    public function setMembers(array $members): self
    {
        $this->members = $members;

        return self::getInstance();
    }

    /**
     * @return array
     */
    public function getTopicMemberIds(): array
    {
        return $this->topicMemberIds;
    }

    /**
     * @param array $topicMemberIds
     *
     * @return ClientKafka
     */
    public function setTopicMemberIds(array $topicMemberIds): self
    {
        $this->topicMemberIds = $topicMemberIds;

        return self::getInstance();
    }

    /**
     * @return array
     */
    public function getMemberIdTopics(): array
    {
        return $this->memberIdTopics;
    }

    /**
     * @param array $memberIdTopics
     *
     * @return ClientKafka
     */
    public function setMemberIdTopics(array $memberIdTopics): self
    {
        $this->memberIdTopics = $memberIdTopics;

        return self::getInstance();
    }

    /**
     * @return bool
     */
    public function isLeader(): bool
    {
        return $this->isLeader;
    }

    /**
     * @param bool $isLeader
     *
     * @return ClientKafka
     */
    public function setIsLeader(bool $isLeader): self
    {
        $this->isLeader = $isLeader;

        return self::getInstance();
    }
}