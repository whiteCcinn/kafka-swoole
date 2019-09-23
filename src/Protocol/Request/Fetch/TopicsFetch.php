<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Fetch;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class TopicsFetch
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
     * @return TopicsFetch
     */
    public function setTopic(String16 $topic): TopicsFetch
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
     * @return TopicsFetch
     */
    public function setPartitions(array $partitions): TopicsFetch
    {
        $this->partitions = $partitions;

        return $this;
    }
}
