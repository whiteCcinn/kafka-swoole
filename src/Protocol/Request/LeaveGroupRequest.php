<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequestOrResponse;
use Kafka\Protocol\Type\String16;

class LeaveGroupRequest extends AbstractRequestOrResponse
{
    /**
     * The ID of the group to leave.
     *
     * @var String16 $groupId
     */
    private $groupId;

    /**
     * The member ID to remove from the group.
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
     * @return LeaveGroupRequest
     */
    public function setGroupId(String16 $groupId): LeaveGroupRequest
    {
        $this->groupId = $groupId;

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
     * @return LeaveGroupRequest
     */
    public function setMemberId(String16 $memberId): LeaveGroupRequest
    {
        $this->memberId = $memberId;

        return $this;
    }
}
