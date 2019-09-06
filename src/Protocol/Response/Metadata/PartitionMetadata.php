<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Metadata;

use Kafka\Protocol\Type\Int16;
use \Kafka\Protocol\Type\Int32;

/**
 * Class PartitionMetadata
 */
class PartitionMetadata
{
    /** @var Int16 $errorCode */
   private $errorCode;

   /** @var Int32 $partitionIndex */
   private $partitionIndex;

   /** @var Int32 $leaderId */
   private $leaderId;

    /** @var Int32[] $replicaNodes */
    private $replicaNodes;

    /** @var Int32[] $isr_nodes */
    private $isrNodes;

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
     * @return PartitionMetadata
     */
    public function setErrorCode(Int16 $errorCode): PartitionMetadata
    {
        $this->errorCode = $errorCode;

        return $this;
    }

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
     * @return PartitionMetadata
     */
    public function setPartitionIndex(Int32 $partitionIndex): PartitionMetadata
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getLeaderId(): Int32
    {
        return $this->leaderId;
    }

    /**
     * @param Int32 $leaderId
     *
     * @return PartitionMetadata
     */
    public function setLeaderId(Int32 $leaderId): PartitionMetadata
    {
        $this->leaderId = $leaderId;

        return $this;
    }

    /**
     * @return Int32[]
     */
    public function getReplicaNodes(): array
    {
        return $this->replicaNodes;
    }

    /**
     * @param Int32[] $replicaNodes
     *
     * @return PartitionMetadata
     */
    public function setReplicaNodes(array $replicaNodes): PartitionMetadata
    {
        $this->replicaNodes = $replicaNodes;

        return $this;
    }

    /**
     * @return Int32[]
     */
    public function getIsrNodes(): array
    {
        return $this->isrNodes;
    }

    /**
     * @param Int32[] $isrNodes
     *
     * @return PartitionMetadata
     */
    public function setIsrNodes(array $isrNodes): PartitionMetadata
    {
        $this->isrNodes = $isrNodes;

        return $this;
    }
}