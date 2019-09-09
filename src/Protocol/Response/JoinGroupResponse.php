<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequestOrResponse;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class JoinGroupResponse extends AbstractRequestOrResponse
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
     * @var
     */
    private $members;
}
