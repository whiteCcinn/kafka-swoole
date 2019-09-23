<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Fetch;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\String16;

class ResponsesFetch
{
    use ToArrayTrait;

    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * null
     *
     * @var PartitionResponsesFetch[] $partition_responses
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
     * @return ResponsesFetch
     */
    public function setTopic(String16 $topic): ResponsesFetch
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PartitionResponsesFetch[]
     */
    public function getPartitionResponses(): array
    {
        return $this->partition_responses;
    }

    /**
     * @param PartitionResponsesFetch[] $partition_responses
     *
     * @return ResponsesFetch
     */
    public function setPartitionResponses(array $partition_responses): ResponsesFetch
    {
        $this->partition_responses = $partition_responses;

        return $this;
    }
}
