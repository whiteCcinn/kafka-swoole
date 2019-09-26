<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Request\OffsetCommit\TopicsOffsetCommit;
use Kafka\Protocol\Type\String16;

class OffsetCommitRequest extends AbstractRequest
{
    /**
     * The unique group identifier.
     *
     * @var String16 $groupId
     */
    private $groupId;

    /**
     * The topics to commit offsets for.
     *
     * @var TopicsOffsetCommit[] $topics
     */
    private $topics;

    /**
     * @return String16
     */
    public function getGroupId(): String16
    {
        return $this->groupId;
    }

    /**
     * @param String16 $groupId
     *
     * @return OffsetCommitRequest
     */
    public function setGroupId(String16 $groupId): OffsetCommitRequest
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return TopicsOffsetCommit[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicsOffsetCommit[] $topics
     *
     * @return OffsetCommitRequest
     */
    public function setTopics(array $topics): OffsetCommitRequest
    {
        $this->topics = $topics;

        return $this;
    }
}
