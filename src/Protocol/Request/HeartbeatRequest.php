<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Type\Int16;
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
     * @var Int16 $generationId
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
     * @return Int16
     */
    public function getGenerationId(): Int16
    {
        return $this->generationId;
    }

    /**
     * @param Int16 $generationId
     *
     * @return HeartbeatRequest
     */
    public function setGenerationId(Int16 $generationId): HeartbeatRequest
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
