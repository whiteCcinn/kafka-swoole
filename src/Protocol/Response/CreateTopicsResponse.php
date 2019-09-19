<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\CreateTopics\TopicsCreateTopics;
use Kafka\Protocol\TraitStructure\ToArrayTrait;

class CreateTopicsResponse extends AbstractResponse
{
    use ToArrayTrait;

    /**
     * @var TopicsCreateTopics $topics
     */
    private $topics;

    /**
     * @return TopicsCreateTopics
     */
    public function getTopics(): TopicsCreateTopics
    {
        return $this->topics;
    }

    /**
     * @param TopicsCreateTopics $topics
     *
     * @return CreateTopicsResponse
     */
    public function setTopics(TopicsCreateTopics $topics): CreateTopicsResponse
    {
        $this->topics = $topics;

        return $this;
    }
}
