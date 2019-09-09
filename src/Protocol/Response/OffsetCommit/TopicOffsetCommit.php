<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\OffsetCommit;

use Kafka\Protocol\Type\String16;

/**
 * Class TopicOffsetCommit
 *
 * @package Kafka\Protocol\Response\Metadata
 */
class TopicOffsetCommit
{
    /**
     * The topic name.
     *
     * @var String16 $name
     */
    private $name;

    /**
     * The responses for each partition in the topic.
     *
     * @var PartitionsOffsetFetch $partitions
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
     * @return TopicOffsetFetch
     */
    public function setName(String16 $name): TopicOffsetFetch
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return PartitionsOffsetFetch
     */
    public function getPartitions(): PartitionsOffsetFetch
    {
        return $this->partitions;
    }

    /**
     * @param PartitionsOffsetFetch $partitions
     *
     * @return TopicOffsetFetch
     */
    public function setPartitions(PartitionsOffsetFetch $partitions): TopicOffsetFetch
    {
        $this->partitions = $partitions;

        return $this;
    }
}