<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class TopicFetch
{
    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * @var PartitionsFetch[] $partitions
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
     * @return TopicFetch
     */
    public function setTopic(String16 $topic): TopicFetch
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionsFetch[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionsFetch[] $partitions
     *
     * @return TopicFetch
     */
    public function setPartitions(array $partitions): TopicFetch
    {
        $this->partitions = $partitions;

        return $this;
    }
}
