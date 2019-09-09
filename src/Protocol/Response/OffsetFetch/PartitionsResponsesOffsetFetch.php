<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\OffsetFetch;

use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;

/**
 * Class PartitionsResponsesOffsetFetch
 *
 * @package Kafka\Protocol\Response\OffsetFetch
 */
class PartitionsResponsesOffsetFetch
{
    /**
     * 	Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * 	Message offset to be committed
     *
     * @var Int64 $offset
     */
    private $offset;

    /**
     * Any associated metadata the client wants to keep.
     *
     * @var String16 $metadata
     */
    private $metadata;

    /**
     * Response error code
     *
     * @var Int16
     */
    private $errorCode;

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
     * @return PartitionsResponsesOffsetFetch
     */
    public function setPartition(Int32 $partition): PartitionsResponsesOffsetFetch
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * @return Int64
     */
    public function getOffset(): Int64
    {
        return $this->offset;
    }

    /**
     * @param Int64 $offset
     *
     * @return PartitionsResponsesOffsetFetch
     */
    public function setOffset(Int64 $offset): PartitionsResponsesOffsetFetch
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return String16
     */
    public function getMetadata(): String16
    {
        return $this->metadata;
    }

    /**
     * @param String16 $metadata
     *
     * @return PartitionsResponsesOffsetFetch
     */
    public function setMetadata(String16 $metadata): PartitionsResponsesOffsetFetch
    {
        $this->metadata = $metadata;

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
     * @return PartitionsResponsesOffsetFetch
     */
    public function setErrorCode(Int16 $errorCode): PartitionsResponsesOffsetFetch
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}