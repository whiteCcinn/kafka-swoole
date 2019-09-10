<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\ListOffsets;

use Kafka\Protocol\Type\String16;

/**
 * Class ResponsesListOffsets
 *
 * @package Kafka\Protocol\Response\ListOffsets
 */
class ResponsesListOffsets
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
     * @var PartitionsResponsesListOffsets[] $partitionResponses
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
     * @return ResponsesListOffsets
     */
    public function setTopic(String16 $topic): ResponsesListOffsets
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionsResponsesListOffsets[]
     */
    public function getPartitionResponses(): array
    {
        return $this->partitionResponses;
    }

    /**
     * @param PartitionsResponsesListOffsets[] $partitionResponses
     *
     * @return ResponsesListOffsets
     */
    public function setPartitionResponses(array $partitionResponses): ResponsesListOffsets
    {
        $this->partitionResponses = $partitionResponses;

        return $this;
    }
}