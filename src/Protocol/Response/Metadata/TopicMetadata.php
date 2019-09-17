<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Metadata;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Int16;
use \Kafka\Protocol\Type\String16;

/**
 * Class RequestMessage
 */
class TopicMetadata
{
    use ToArrayTrait;

    /** @var Int16 $errorCode */
   private $errorCode;

   /** @var String16 $name */
   private $name;

   /** @var PartitionMetadata[] $partitions */
   private $partitions;

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
     * @return TopicMetadata
     */
    public function setErrorCode(Int16 $errorCode): TopicMetadata
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return String16
     */
    public function getName(): String16
    {
        return $this->name;
    }

    /**
     * @param String16 $name
     *
     * @return TopicMetadata
     */
    public function setName(String16 $name): TopicMetadata
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return PartitionMetadata[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionMetadata[] $partitions
     *
     * @return TopicMetadata
     */
    public function setPartitions(array $partitions): TopicMetadata
    {
        $this->partitions = $partitions;

        return $this;
    }
}