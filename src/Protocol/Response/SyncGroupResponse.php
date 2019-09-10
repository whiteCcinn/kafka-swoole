<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;

class SyncGroupResponse extends AbstractRequest
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * The member assignment.
     *
     * @var Bytes32 $assignment
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
     * @return Bytes32
     */
    public function getAssignment(): Bytes32
    {
        return $this->assignment;
    }

    /**
     * @param Bytes32 $assignment
     *
     * @return SyncGroupResponse
     */
    public function setAssignment(Bytes32 $assignment): SyncGroupResponse
    {
        $this->assignment = $assignment;

        return $this;
    }
}
