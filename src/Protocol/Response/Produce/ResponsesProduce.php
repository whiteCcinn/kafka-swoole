<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Protocol\Type\String16;

class ResponsesProduce
{
    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * null
     *
     * @var PartitionResponsesProduce[] $partition_responses
     */
    private $partition_responses;

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
     * @return ResponsesProduce
     */
    public function setTopic(String16 $topic): ResponsesProduce
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionResponsesProduce[]
     */
    public function getPartitionResponses(): array
    {
        return $this->partition_responses;
    }

    /**
     * @param PartitionResponsesProduce[] $partition_responses
     *
     * @return ResponsesProduce
     */
    public function setPartitionResponses(array $partition_responses): ResponsesProduce
    {
        $this->partition_responses = $partition_responses;

        return $this;
    }
}
