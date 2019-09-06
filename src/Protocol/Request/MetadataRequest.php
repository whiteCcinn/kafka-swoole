<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequestOrResponse;

use Kafka\Protocol\Type\String16;

class MetadataRequest extends AbstractRequestOrResponse
{
    /** @var String16[] $topicName */
    private $topicName;

    /**
     * @return String16[]
     */
    public function getTopicName(): array
    {
        return $this->topicName;
    }

    /**
     * @param String16[] $topicName
     *
     * @return MetadataRequest
     */
    public function setTopicName(array $topicName): MetadataRequest
    {
        $this->topicName = $topicName;

        return $this;
    }
}
