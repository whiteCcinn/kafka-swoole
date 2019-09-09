<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

class PartitionResponsesProduce
{
    /**
     * @var Int32 $partition
     */
    private $partition;

    /**
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * @var Int64 $baseOffset
     */
    private $baseOffset;

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
     * @return PartitionResponsesProduce
     */
    public function setPartition(Int32 $partition): PartitionResponsesProduce
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
     * @return PartitionResponsesProduce
     */
    public function setErrorCode(Int16 $errorCode): PartitionResponsesProduce
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return Int64
     */
    public function getBaseOffset(): Int64
    {
        return $this->baseOffset;
    }

    /**
     * @param Int64 $baseOffset
     *
     * @return PartitionResponsesProduce
     */
    public function setBaseOffset(Int64 $baseOffset): PartitionResponsesProduce
    {
        $this->baseOffset = $baseOffset;

        return $this;
    }
}
