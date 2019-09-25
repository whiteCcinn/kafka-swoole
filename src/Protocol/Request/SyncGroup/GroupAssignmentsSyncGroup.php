<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\SyncGroup;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Protocol\CommonRequest;
use Kafka\Protocol\Type\String16;

class GroupAssignmentsSyncGroup
{
    /**
     * The ID of the member to assign.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The member assignment.
     *
     * @var MemberAssignmentsSyncGroup $assignment
     */
    private $memberAssignment;

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
     * @return GroupAssignmentsSyncGroup
     */
    public function setMemberId(String16 $memberId): GroupAssignmentsSyncGroup
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * @return MemberAssignmentsSyncGroup
     */
    public function getMemberAssignment(): MemberAssignmentsSyncGroup
    {
        return $this->memberAssignment;
    }

    /**
     * @param MemberAssignmentsSyncGroup $memberAssignment
     *
     * @return GroupAssignmentsSyncGroup
     */
    public function setMemberAssignment(MemberAssignmentsSyncGroup $memberAssignment): GroupAssignmentsSyncGroup
    {
        $this->memberAssignment = $memberAssignment;

        return $this;
    }

    /**
     * @param $protocol
     *
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function onMemberAssignment(&$protocol)
    {
        $commentRequest = new CommonRequest();
        $data = $commentRequest->packProtocol(MemberAssignmentsSyncGroup::class, $this->memberAssignment);
        $protocol .= pack(ProtocolTypeEnum::getTextByCode(ProtocolTypeEnum::B32), strlen($data)) . $data;
    }
}
