<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class AssignmentsSyncGroup
{
    /**
     * The ID of the member to assign.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * 	The member assignment.
     *
     * @var Bytes32 $assignment
     */
    private $assignment;
}
