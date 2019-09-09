<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Fetch;

use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

class PartitionHeaderFetch
{
    /**
     * Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * 	Response error code
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * 	Last committed offset.
     *
     * @var Int64 $highWatermark
     */
    private $highWatermark;

    /**
     * @return Int32
     */
    public function getPartition(): Int32
    {
        return $this->partition;
    }

    /**
     * @param Int32 $partition
     *
     * @return PartitionHeaderFetch
     */
    public function setPartition(Int32 $partition): PartitionHeaderFetch
    {
        $this->partition = $partition;

        return $this;
    }

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
     * @return PartitionHeaderFetch
     */
    public function setErrorCode(Int16 $errorCode): PartitionHeaderFetch
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return Int64
     */
    public function getHighWatermark(): Int64
    {
        return $this->highWatermark;
    }

    /**
     * @param Int64 $highWatermark
     *
     * @return PartitionHeaderFetch
     */
    public function setHighWatermark(Int64 $highWatermark): PartitionHeaderFetch
    {
        $this->highWatermark = $highWatermark;

        return $this;
    }
}
