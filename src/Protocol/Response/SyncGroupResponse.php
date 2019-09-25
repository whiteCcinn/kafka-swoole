<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\SyncGroup\MemberAssignmentsSyncGroup;
use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Int16;

class SyncGroupResponse extends AbstractResponse
{
    use ToArrayTrait;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * The member assignment.
     *
     * @var MemberAssignmentsSyncGroup $assignment
     */
    private $assignment;

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
     * @return SyncGroupResponse
     */
    public function setErrorCode(Int16 $errorCode): SyncGroupResponse
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return MemberAssignmentsSyncGroup
     */
    public function getAssignment(): MemberAssignmentsSyncGroup
    {
        return $this->assignment;
    }

    /**
     * @param MemberAssignmentsSyncGroup $assignment
     *
     * @return SyncGroupResponse
     */
    public function setAssignment(MemberAssignmentsSyncGroup $assignment): SyncGroupResponse
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * @param $protocol
     *
     * @return bool
     */
    public function onAssignment(&$protocol): bool
    {
        $data = unpack(ProtocolTypeEnum::getTextByCode(ProtocolTypeEnum::B32),
            substr($protocol, 0, ProtocolTypeEnum::B32));
        $data = is_array($data) ? array_shift($data) : $data;
        $length = $data;
        $protocol = substr($protocol, ProtocolTypeEnum::B32, $length);

        return false;
    }
}
