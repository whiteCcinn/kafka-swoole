<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\ListOffsets;

use Kafka\Protocol\Type\String16;

class TopicsListsOffsets
{
    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * Partitions to list offsets.
     *
     * @var PartitionsListsOffsets[] $partitions
     */
    private $partitions;

    /**
     * @return String16
     */
    public function getTopic(): String16
    {
        return $this->topic;
    }

    /**
     * @param String16 $topic
     *
     * @return TopicsListsOffsets
     */
    public function setTopic(String16 $topic): TopicsListsOffsets
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionsListsOffsets[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionsListsOffsets[] $partitions
     *
     * @return TopicsListsOffsets
     */
    public function setPartitions(array $partitions): TopicsListsOffsets
    {
        $this->partitions = $partitions;

        return $this;
    }
}
