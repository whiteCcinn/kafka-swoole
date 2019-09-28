<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\ListOffsets;

use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

class PartitionsListsOffsets
{
    /**
     * Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * The target timestamp for the partition.
     *
     * @var Int64 $timestamp
     */
    private $timestamp;

    /**
     * Maximum offsets to return.
     *
     * @var Int32 $maxNumOffsets
     */
    private $maxNumOffsets;

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
     * @return PartitionsListsOffsets
     */
    public function setPartition(Int32 $partition): PartitionsListsOffsets
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * @return Int64
     */
    public function getTimestamp(): Int64
    {
        return $this->timestamp;
    }

    /**
     * @param Int64 $timestamp
     *
     * @return PartitionsListsOffsets
     */
    public function setTimestamp(Int64 $timestamp): PartitionsListsOffsets
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getMaxNumOffsets(): Int32
    {
        if ($this->maxNumOffsets === null) {
            $this->setMaxNumOffsets(Int32::value(99999999));
        }

        return $this->maxNumOffsets;
    }

    /**
     * @param Int32 $maxNumOffsets
     *
     * @return PartitionsListsOffsets
     */
    public function setMaxNumOffsets(Int32 $maxNumOffsets): PartitionsListsOffsets
    {
        $this->maxNumOffsets = $maxNumOffsets;

        return $this;
    }
}
