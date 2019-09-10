<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Request\OffsetFetch\TopicsOffsetFetch;
use Kafka\Protocol\Type\String16;

class OffsetFetchRequest extends AbstractRequest
{
    /**
     * The unique group identifier
     *
     * @var String16 $groupId
     */
    private $groupId;

    /**
     * Topics to fetch offsets.
     *
     * @var TopicsOffsetFetch[] $topics
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
     * @return OffsetFetchRequest
     */
    public function setGroupId(String16 $groupId): OffsetFetchRequest
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return TopicsOffsetFetch[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicsOffsetFetch[] $topics
     *
     * @return OffsetFetchRequest
     */
    public function setTopics(array $topics): OffsetFetchRequest
    {
        $this->topics = $topics;

        return $this;
    }
}
