<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Request\Metadata\AssignmentsSyncGroup;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class SyncGroupRequest extends AbstractRequest
{
    /**
     * The unique group identifier.
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
     * The member ID assigned by the group.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * Each assignment.
     *
     * @var AssignmentsSyncGroup $assignments
     */
    private $assignments;

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
     * @return SyncGroupRequest
     */
    public function setGroupId(String16 $groupId): SyncGroupRequest
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
     * @return SyncGroupRequest
     */
    public function setGenerationId(Int32 $generationId): SyncGroupRequest
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
     * @return SyncGroupRequest
     */
    public function setMemberId(String16 $memberId): SyncGroupRequest
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * @return AssignmentsSyncGroup
     */
    public function getAssignments(): AssignmentsSyncGroup
    {
        return $this->assignments;
    }

    /**
     * @param AssignmentsSyncGroup $assignments
     *
     * @return SyncGroupRequest
     */
    public function setAssignments(AssignmentsSyncGroup $assignments): SyncGroupRequest
    {
        $this->assignments = $assignments;

        return $this;
    }
}
