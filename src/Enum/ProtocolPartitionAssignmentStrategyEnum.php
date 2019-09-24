<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class ProtocolPartitionAssignmentStrategyEnum
 *
 * @package Kafka\Enum
 */
class ProtocolPartitionAssignmentStrategyEnum extends AbstractEnum
{
    /**
     * @message("Range")
     */
    public const RANGE_ASSIGNOR = 0;

    /**
     * @message("RoundRobin")
     */
    public const ROUND_ROBIN_ASSIGNOR = 1;

    /**
     * @message("Sticky")
     */
    public const STICKY_ASSIGNOR = 2;
}