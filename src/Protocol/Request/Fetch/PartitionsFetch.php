<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Fetch;

use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

class PartitionsFetch
{
    /**
     * Topic partition id
     *
     * @var Int32 $topic
     */
    private $partition;

    /**
     * Message offset.
     *
     * @var Int64 $fetchOffset
     */
    private $fetchOffset;

    /**
     * 	Maximum bytes to fetch.
     *
     * @var Int32 $partitionMaxBytes
     */
    private $partitionMaxBytes;

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
     * @return PartitionsFetch
     */
    public function setPartition(Int32 $partition): PartitionsFetch
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * @return Int64
     */
    public function getFetchOffset(): Int64
    {
        return $this->fetchOffset;
    }

    /**
     * @param Int64 $fetchOffset
     *
     * @return PartitionsFetch
     */
    public function setFetchOffset(Int64 $fetchOffset): PartitionsFetch
    {
        $this->fetchOffset = $fetchOffset;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getPartitionMaxBytes(): Int32
    {
        return $this->partitionMaxBytes;
    }

    /**
     * @param Int32 $partitionMaxBytes
     *
     * @return PartitionsFetch
     */
    public function setPartitionMaxBytes(Int32 $partitionMaxBytes): PartitionsFetch
    {
        $this->partitionMaxBytes = $partitionMaxBytes;

        return $this;
    }
}
