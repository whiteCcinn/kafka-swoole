<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Int16;

class LeaveGroupResponse extends AbstractResponse
{
    use ToArrayTrait;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

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
     * @return LeaveGroupResponse
     */
    public function setErrorCode(Int16 $errorCode): LeaveGroupResponse
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}
