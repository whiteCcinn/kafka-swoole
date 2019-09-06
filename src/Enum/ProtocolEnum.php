<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class ProtocolEnum
 */
class ProtocolEnum extends AbstractEnum
{
    /**
     * @message("ProduceRequest")
     */
    public const PRODUCE = 0;

    /**
     * @message("FetchRequest")
     */
    public const FETCH = 1;

    /**
     * @message("ListOffsetsRequest")
     */
    public const LIST_OFFSETS = 2;

    /**
     * @message("")
     */
    public const METADATA = 4;

    /**
     * @message("")
     */
    public const OFFSET_COMMIT = 8;

    /**
     * @message("")
     */
    public const OFFSET_FETCH = 9;

    /**
     * @message("")
     */
    public const FIND_COORDINATOR = 10;

    /**
     * @message("")
     */
    public const JOIN_GROUP = 11;

    /**
     * @message("")
     */
    public const HEARTBEAT = 12;

    /**
     * @message("")
     */
    public const SYNC_GROUP = 14;
}