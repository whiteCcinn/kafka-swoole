<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\OffsetFetch;

use Kafka\Protocol\Type\Int32;

class PartitionsOffsetFetch
{
    /**
     * Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

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
     * @return PartitionsOffsetFetch
     */
    public function setPartition(Int32 $partition): PartitionsOffsetFetch
    {
        $this->partition = $partition;

        return $this;
    }
}
