<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;

use Kafka\Protocol\Request\JoinGroup\ProtocolsJoinGroup;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class JoinGroupRequest extends AbstractRequest
{
    /**
     * The group identifier.
     *
     * @var String16 $group_id
     */
    private $groupId;

    /**
     * The coordinator considers the consumer dead if it receives no heartbeat after this timeout in milliseconds.
     *
     * @var Int32 $sessionTimeoutMs
     */
    private $sessionTimeoutMs;

    /**
     * The member id assigned by the group coordinator.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The unique name the for class of protocols implemented by the group we want to join.
     *
     * @var String16 $protocolType
     */
    private $protocolType;


    /**
     * The list of protocols that the member supports.
     *
     * @var ProtocolsJoinGroup[] $protocols
     */
    private $protocols;

    /**
     * @return String16
     */
    public function getGroupId(): String16
    {
        return $this->groupId;
    }

    /**
     * @param String16 $groupId
     *
     * @return JoinGroupRequest
     */
    public function setGroupId(String16 $groupId): JoinGroupRequest
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getSessionTimeoutMs(): Int32
    {
        return $this->sessionTimeoutMs;
    }

    /**
     * @param Int32 $sessionTimeoutMs
     *
     * @return JoinGroupRequest
     */
    public function setSessionTimeoutMs(Int32 $sessionTimeoutMs): JoinGroupRequest
    {
        $this->sessionTimeoutMs = $sessionTimeoutMs;

        return $this;
    }

    /**
     * @return String16
     */
    public function getMemberId(): String16
    {
        return $this->memberId;
    }

    /**
     * @param String16 $memberId
     *
     * @return JoinGroupRequest
     */
    public function setMemberId(String16 $memberId): JoinGroupRequest
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * @return String16
     */
    public function getProtocolType(): String16
    {
        return $this->protocolType;
    }

    /**
     * @param String16 $protocolType
     *
     * @return JoinGroupRequest
     */
    public function setProtocolType(String16 $protocolType): JoinGroupRequest
    {
        $this->protocolType = $protocolType;

        return $this;
    }

    /**
     * @return ProtocolsJoinGroup[]
     */
    public function getProtocols(): array
    {
        return $this->protocols;
    }

    /**
     * @param ProtocolsJoinGroup[] $protocols
     *
     * @return JoinGroupRequest
     */
    public function setProtocols(array $protocols): JoinGroupRequest
    {
        $this->protocols = $protocols;

        return $this;
    }
}
