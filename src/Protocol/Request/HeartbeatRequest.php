<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class HeartbeatRequest extends AbstractRequest
{
    /**
     * The group id.
     *
     * @var String16 $groupId
     */
    private $groupId;

    /**
     * The generation of the group.
     *
     * @var Int32 $generationId
     */
    private $generationId;

    /**
     * The member ID.
     *
     * @var String16 $memberId
     */
    private $memberId;

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
     * @return HeartbeatRequest
     */
    public function setGroupId(String16 $groupId): HeartbeatRequest
    {
        $this->groupId = $groupId;

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
     * @return HeartbeatRequest
     */
    public function setGenerationId(Int32 $generationId): HeartbeatRequest
    {
        $this->generationId = $generationId;

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
     * @return HeartbeatRequest
     */
    public function setMemberId(String16 $memberId): HeartbeatRequest
    {
        $this->memberId = $memberId;

        return $this;
    }
}
