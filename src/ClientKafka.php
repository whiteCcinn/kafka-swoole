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
     * @var array $topicPartitionSocket
     */
    private $topicPartitionSocket = [
        'topic' => [
            0 => null
        ]
    ];

    /**
     * @var array $topicPartitionSocket
     */
    private $topicPartitionListOffsets = [
        'topic' => [
            0 => [
                'offset'        => 0,
                'highWatermark' => 0
            ]
        ]
    ];

    /**
     * @var array
     */
    private $selfLeaderTopicPartition = [
        'leaderId' => ['topic' => ['partition']]
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
     * Just leader has
     *
     * @var MembersJoinGroup[] $members
     */
    private $members = [];

    /**
     * Just leader has
     *
     * @var array
     */
    private $topicMemberIds = [];

    /**
     * Just leader has
     *
     * @var array
     */
    private $memberIdTopics = [];

    /**
     * @var array
     * ['topic'=>[partition,...]]
     */
    private $selfTopicPartition = [];

    /**
     * @var array
     * ['topic'=>['partition'=>offset]]
     */
    private $topicPartitionOffset = [];

    /**
     * @var bool $isRebalancing
     */
    private $isRebalancing = false;

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

    /**
     * @return array
     */
    public function getSelfTopicPartition(): array
    {
        return $this->selfTopicPartition;
    }

    /**
     * @param array $selfTopicPartition
     *
     * @return ClientKafka
     */
    public function setSelfTopicPartition(array $selfTopicPartition): ClientKafka
    {
        $this->selfTopicPartition = $selfTopicPartition;

        return $this;
    }


    /**
     * @param string $topic
     * @param int    $partition
     * @param Socket $socket
     *
     * @return ClientKafka
     */
    public function setTopicPartitionSocket(string $topic, int $partition, Socket $socket): self
    {
        $this->topicPartitionSocket[$topic][$partition] = $socket;

        return self::getInstance();
    }

    /**
     * @param string $topic
     * @param int    $partition
     *
     * @return Socket
     */
    public function getSocketByTopicPartition(string $topic, int $partition): Socket
    {
        return $this->topicPartitionSocket[$topic][$partition];
    }

    /**
     * @param string $topic
     * @param int    $partition
     *
     * @return array
     */
    public function getTopicPartitionListOffsets(string $topic, int $partition): array
    {
        return $this->topicPartitionListOffsets[$topic][$partition];
    }

    /**
     * @param string $topic
     * @param int    $partition
     * @param int    $offset
     * @param int    $highWatermark
     *
     * @return ClientKafka
     */
    public function setTopicPartitionListOffsets(string $topic, int $partition, int $offset, int $highWatermark): self
    {
        $this->topicPartitionListOffsets[$topic][$partition] = [
            'offset'        => $offset,
            'highWatermark' => $highWatermark
        ];

        return $this;
    }

    /**
     * @param string $topic
     * @param int    $partition
     *
     * @return int
     */
    public function getTopicPartitionOffsetByTopicPartition(string $topic, int $partition): int
    {
        return $this->topicPartitionOffset[$topic][$partition];
    }

    /**
     * @param string $topic
     * @param int    $partition
     * @param int    $offset
     *
     * @return ClientKafka
     */
    public function setTopicPartitionOffset(string $topic, int $partition, int $offset): self
    {
        $this->topicPartitionOffset[$topic][$partition] = $offset;

        return $this;
    }

    /**
     * @return array
     */
    public function getSelfLeaderTopicPartition(): array
    {
        return $this->selfLeaderTopicPartition;
    }

    /**
     * @param array $selfLeaderTopicPartition
     *
     * @return ClientKafka
     */
    public function setSelfLeaderTopicPartition(array $selfLeaderTopicPartition): ClientKafka
    {
        $this->selfLeaderTopicPartition = $selfLeaderTopicPartition;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRebalancing(): bool
    {
        return $this->isRebalancing;
    }

    /**
     * @param bool $isRebalancing
     *
     * @return ClientKafka
     */
    public function setIsRebalancing(bool $isRebalancing): ClientKafka
    {
        $this->isRebalancing = $isRebalancing;

        return $this;
    }
}