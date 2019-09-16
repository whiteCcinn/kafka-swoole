<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class DataProduce
{
    /**
     * Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * null todo
     *
     * @var String16[] $recordSet
     */
    private $recordSet;

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
     * @return DataProduce
     */
    public function setPartition(Int32 $partition): DataProduce
    {
        $this->partition = $partition;

        return $this;
    }
}
