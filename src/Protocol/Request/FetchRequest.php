<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequestOrResponse;
use Kafka\Protocol\Request\Metadata\TopicFetch;
use Kafka\Protocol\Type\Int32;

class FetchRequest extends AbstractRequestOrResponse
{
    /**
     * Broker id of the follower. For normal consumers, use -1.
     *
     * @var Int32 $replicaId
     */
    private $replicaId;

    /**
     * Maximum time in ms to wait for the response.
     *
     * @var Int32 $maxWaitTime
     */
    private $maxWaitTime;

    /**
     * Topics to fetch in the order provided.
     *
     * @var Int32 $minBytes
     */
    private $minBytes;

    /**
     * Topics to fetch in the order provided.
     *
     * @var TopicFetch $topics
     */
    private $topics;

    /**
     * @return Int32
     */
    public function getReplicaId(): Int32
    {
        return $this->replicaId;
    }

    /**
     * @param Int32 $replicaId
     *
     * @return FetchRequest
     */
    public function setReplicaId(Int32 $replicaId): FetchRequest
    {
        $this->replicaId = $replicaId;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getMaxWaitTime(): Int32
    {
        return $this->maxWaitTime;
    }

    /**
     * @param Int32 $maxWaitTime
     *
     * @return FetchRequest
     */
    public function setMaxWaitTime(Int32 $maxWaitTime): FetchRequest
    {
        $this->maxWaitTime = $maxWaitTime;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getMinBytes(): Int32
    {
        return $this->minBytes;
    }

    /**
     * @param Int32 $minBytes
     *
     * @return FetchRequest
     */
    public function setMinBytes(Int32 $minBytes): FetchRequest
    {
        $this->minBytes = $minBytes;

        return $this;
    }

    /**
     * @return TopicFetch
     */
    public function getTopics(): TopicFetch
    {
        return $this->topics;
    }

    /**
     * @param TopicFetch $topics
     *
     * @return FetchRequest
     */
    public function setTopics(TopicFetch $topics): FetchRequest
    {
        $this->topics = $topics;

        return $this;
    }
}
