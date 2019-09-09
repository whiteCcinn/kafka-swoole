<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class MembersJoinGroup
{
    /**
     * The group member ID.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The group member metadata.
     *
     * @var Bytes32 $metadata
     */
    private $metadata;
}
