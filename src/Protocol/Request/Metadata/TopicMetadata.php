<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\String16;

class TopicMetadata
{
    /** @var String16 $topicName */
    private $topicName;

    /**
     * @return String16
     */
    public function getTopicName(): String16
    {
        return $this->topicName;
    }

    /**
     * @param String16 $topicName
     *
     * @return TopicMetadata
     */
    public function setTopicName(String16 $topicName): TopicMetadata
    {
        $this->topicName = $topicName;

        return $this;
    }
}
