<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\OffsetFetch;

use Kafka\Protocol\Type\String16;

/**
 * Class ResponsesOffsetFetch
 *
 * @package Kafka\Protocol\Response\OffsetFetch
 */
class ResponsesOffsetFetch
{
    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * Responses by partition for fetched offsets
     *
     * @var PartitionsResponsesOffsetFetch[] $partitionResponses
     */
    private $partitionResponses;

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
     * @return ResponsesOffsetFetch
     */
    public function setTopic(String16 $topic): ResponsesOffsetFetch
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionsResponsesOffsetFetch[]
     */
    public function getPartitionResponses(): array
    {
        return $this->partitionResponses;
    }

    /**
     * @param PartitionsResponsesOffsetFetch[] $partitionResponses
     *
     * @return ResponsesOffsetFetch
     */
    public function setPartitionResponses(array $partitionResponses): ResponsesOffsetFetch
    {
        $this->partitionResponses = $partitionResponses;

        return $this;
    }
}