<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\OffsetCommit;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\String16;

/**
 * Class TopicOffsetCommit
 *
 * @package Kafka\Protocol\Response\Metadata
 */
class TopicOffsetCommit
{
    use ToArrayTrait;
    /**
     * The topic name.
     *
     * @var String16 $name
     */
    private $name;

    /**
     * The responses for each partition in the topic.
     *
     * @var PartitionsOffsetCommit[] $partitions
     */
    private $partitions;

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
     * @return TopicOffsetCommit
     */
    public function setName(String16 $name): TopicOffsetCommit
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return PartitionsOffsetCommit[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionsOffsetCommit[] $partitions
     *
     * @return TopicOffsetCommit
     */
    public function setPartitions(array $partitions): TopicOffsetCommit
    {
        $this->partitions = $partitions;

        return $this;
    }
}