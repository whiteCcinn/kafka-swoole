<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequestOrResponse;
use Kafka\Protocol\Request\ListOffsets\TopicsListsOffsets;
use Kafka\Protocol\Type\Int32;

class ListOffsetsRequest extends AbstractRequestOrResponse
{
    /**
     * @var Int32 $replicaId
     */
    private $replicaId;

    /**
     * @var TopicsListsOffsets[] $topics
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
     * @return ListOffsetsRequest
     */
    public function setReplicaId(Int32 $replicaId): ListOffsetsRequest
    {
        $this->replicaId = $replicaId;

        return $this;
    }

    /**
     * @return TopicsListsOffsets[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicsListsOffsets[] $topics
     *
     * @return ListOffsetsRequest
     */
    public function setTopics(array $topics): ListOffsetsRequest
    {
        $this->topics = $topics;

        return $this;
    }
}
