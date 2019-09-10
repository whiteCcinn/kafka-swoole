<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\ListOffsets;

use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

/**
 * Class PartitionsResponsesListOffsets
 *
 * @package Kafka\Protocol\Response\ListOffsets
 */
class PartitionsResponsesListOffsets
{
    /**
     * 	Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * A list of offsets.
     *
     * @var Int64[] $offsets
     */
    private $offsets;

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
     * @return PartitionsResponsesListOffsets
     */
    public function setPartition(Int32 $partition): PartitionsResponsesListOffsets
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * @return Int64[]
     */
    public function getOffsets(): array
    {
        return $this->offsets;
    }

    /**
     * @param Int64[] $offsets
     *
     * @return PartitionsResponsesListOffsets
     */
    public function setOffsets(array $offsets): PartitionsResponsesListOffsets
    {
        $this->offsets = $offsets;

        return $this;
    }
}