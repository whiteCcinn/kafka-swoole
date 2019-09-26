<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\OffsetCommit;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;

/**
 * Class PartitionsOffsetCommit
 *
 * @package Kafka\Protocol\Response\Metadata
 */
class PartitionsOffsetCommit
{
    use ToArrayTrait;
    /**
     * The partition index.
     *
     * @var Int32 $partitionIndex
     */
    private $partitionIndex;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * @return Int32
     */
    public function getPartitionIndex(): Int32
    {
        return $this->partitionIndex;
    }

    /**
     * @param Int32 $partitionIndex
     *
     * @return PartitionsOffsetCommit
     */
    public function setPartitionIndex(Int32 $partitionIndex): PartitionsOffsetCommit
    {
        $this->partitionIndex = $partitionIndex;

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
     * @return PartitionsOffsetCommit
     */
    public function setErrorCode(Int16 $errorCode): PartitionsOffsetCommit
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}