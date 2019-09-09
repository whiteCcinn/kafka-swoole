<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\OffsetFetch;

use Kafka\Protocol\Type\String16;

class TopicsOffsetFetch
{
    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * Partitions to fetch offsets.
     *
     * @var PartitionsOffsetFetch[] $partitions
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
     * @return TopicsOffsetFetch
     */
    public function setTopic(String16 $topic): TopicsOffsetFetch
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionsOffsetFetch[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionsOffsetFetch[] $partitions
     *
     * @return TopicsOffsetFetch
     */
    public function setPartitions(array $partitions): TopicsOffsetFetch
    {
        $this->partitions = $partitions;

        return $this;
    }
}
