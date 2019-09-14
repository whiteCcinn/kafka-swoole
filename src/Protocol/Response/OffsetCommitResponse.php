<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\OffsetCommit\TopicOffsetCommit;

class OffsetCommitResponse extends AbstractResponse
{
    /**
     * @var TopicOffsetCommit[] $topics
     */
    private $topics;

    /**
     * @return TopicOffsetCommit[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicOffsetCommit[] $topics
     *
     * @return OffsetCommitResponse
     */
    public function setTopics(array $topics): OffsetCommitResponse
    {
        $this->topics = $topics;

        return $this;
    }
}
