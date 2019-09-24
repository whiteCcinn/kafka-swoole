<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\String16;

class TopicJoinGroup
{
    use ToArrayTrait;

    /**
     * @var String16 $topic
     */
    private $topic;

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
     * @return TopicJoinGroup
     */
    public function setTopic(String16 $topic): TopicJoinGroup
    {
        $this->topic = $topic;

        return $this;
    }
}
