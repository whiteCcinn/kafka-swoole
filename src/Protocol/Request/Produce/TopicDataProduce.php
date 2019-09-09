<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class TopicDataProduce
{
    /**
     * Name of topic
     *
     * @var String16 $topic
     */
    private $topic;

    /**
     * null
     *
     * @var DataProduce[] $data;
     */
    private $data;

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
     * @return TopicDataProduce
     */
    public function setTopic(String16 $topic): TopicDataProduce
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return DataProduce[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param DataProduce[] $data
     *
     * @return TopicDataProduce
     */
    public function setData(array $data): TopicDataProduce
    {
        $this->data = $data;

        return $this;
    }
}
