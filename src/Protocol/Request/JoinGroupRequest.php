<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequestOrResponse;

use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class JoinGroupRequest extends AbstractRequestOrResponse
{
    /**
     * The group identifier.
     *
     * @var String16 $group_id
     */
    private $groupId;

    /**
     * The coordinator considers the consumer dead if it receives no heartbeat after this timeout in milliseconds.
     *
     * @var Int32 $sessionTimeoutMs
     */
    private $sessionTimeoutMs;

    /**
     * The member id assigned by the group coordinator.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The unique name the for class of protocols implemented by the group we want to join.
     *
     * @var String16 $protocolType
     */
    private $protocolType;


    /**
     * The list of protocols that the member supports.
     *
     * @var
     */
    private $protocols;
}
