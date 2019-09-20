<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Request\Produce\TopicDataProduce;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;

class ProduceRequest extends AbstractRequest
{
    /**
     * The number of acknowledgments the producer requires the leader to have received before considering a request
     * complete. Allowed values: 0 for no acknowledgments, 1 for only the leader and -1 for the full ISR.
     *
     * @var Int16 $acks
     */
    private $acks;

    /**
     * timeout The time to await a response in ms.
     *
     * @var Int32 $timeout
     */
    private $timeout;

    /**
     * null
     *
     * @var TopicDataProduce[] $topicData
     */
    private $topicData;

    /**
     * @return Int16
     */
    public function getAcks(): Int16
    {
        return $this->acks;
    }

    /**
     * @param Int16 $acks
     *
     * @return ProduceRequest
     */
    public function setAcks(Int16 $acks): ProduceRequest
    {
        $this->acks = $acks;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getTimeout(): Int32
    {
        return $this->timeout;
    }

    /**
     * @param Int32 $timeout
     *
     * @return ProduceRequest
     */
    public function setTimeout(Int32 $timeout): ProduceRequest
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return TopicDataProduce[]
     */
    public function getTopicData(): array
    {
        return $this->topicData;
    }

    /**
     * @param TopicDataProduce[] $topicData
     *
     * @return ProduceRequest
     */
    public function setTopicData(array $topicData): ProduceRequest
    {
        $this->topicData = $topicData;

        return $this;
    }
}
