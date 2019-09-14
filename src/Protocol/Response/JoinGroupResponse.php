<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\JoinGroup\MembersJoinGroup;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class JoinGroupResponse extends AbstractResponse
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * The generation ID of the group.
     *
     * @var Int32 $generationId
     */
    private $generationId;

    /**
     * The group protocol selected by the coordinator.
     *
     * @var String16 $protocolName
     */
    private $protocolName;

    /**
     * 	The leader of the group.
     *
     * @var String16 $leader
     */
    private $leader;

    /**
     * The member ID assigned by the group coordinator.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * @var MembersJoinGroup $members
     */
    private $members;

    /**
     * @return Int16
     */
    public function getErrorCode(): Int16
    {
        return $this->errorCode;
    }

    /**
     * @param Int16 $errorCode
     *
     * @return JoinGroupResponse
     */
    public function setErrorCode(Int16 $errorCode): JoinGroupResponse
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getGenerationId(): Int32
    {
        return $this->generationId;
    }

    /**
     * @param Int32 $generationId
     *
     * @return JoinGroupResponse
     */
    public function setGenerationId(Int32 $generationId): JoinGroupResponse
    {
        $this->generationId = $generationId;

        return $this;
    }

    /**
     * @return String16
     */
    public function getProtocolName(): String16
    {
        return $this->protocolName;
    }

    /**
     * @param String16 $protocolName
     *
     * @return JoinGroupResponse
     */
    public function setProtocolName(String16 $protocolName): JoinGroupResponse
    {
        $this->protocolName = $protocolName;

        return $this;
    }

    /**
     * @return String16
     */
    public function getLeader(): String16
    {
        return $this->leader;
    }

    /**
     * @param String16 $leader
     *
     * @return JoinGroupResponse
     */
    public function setLeader(String16 $leader): JoinGroupResponse
    {
        $this->leader = $leader;

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
     * @return JoinGroupResponse
     */
    public function setMemberId(String16 $memberId): JoinGroupResponse
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * @return MembersJoinGroup
     */
    public function getMembers(): MembersJoinGroup
    {
        return $this->members;
    }

    /**
     * @param MembersJoinGroup $members
     *
     * @return JoinGroupResponse
     */
    public function setMembers(MembersJoinGroup $members): JoinGroupResponse
    {
        $this->members = $members;

        return $this;
    }
}
