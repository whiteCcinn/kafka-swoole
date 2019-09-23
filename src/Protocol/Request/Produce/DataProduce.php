<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Produce;

use Kafka\Protocol\CommonRequest;
use Kafka\Protocol\Type\Int32;

/**
 * Class DataProduce
 *
 *
 * @package Kafka\Protocol\Request\Metadata
 */
class DataProduce
{
    /**
     * Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * @var MessageSetProduce[] $messageSet
     */
    private $messageSet;

    /**
     * @return Int32
     */
    public function getPartition(): Int32
    {
        return $this->partition;
    }

    /**
     * @param Int32 $partition
     *
     * @return DataProduce
     */
    public function setPartition(Int32 $partition): DataProduce
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * @return MessageSetProduce[]
     */
    public function getMessageSet(): array
    {
        return $this->messageSet;
    }

    /**
     * @param MessageSetProduce[] $messageSet
     *
     * @return DataProduce
     */
    public function setMessageSet(array $messageSet): DataProduce
    {
        $this->messageSet = $messageSet;

        return $this;
    }

    /**
     * @param $protocol
     *
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function onMessageSet(&$protocol)
    {
        $commentRequest = new CommonRequest();
        $data = '';
        foreach ($this->getMessageSet() as $messageSet) {
            $data .= $commentRequest->packProtocol(MessageSetProduce::class, $messageSet);
        }

        $protocol .= pack(Int32::getWrapperProtocol(), strlen($data)) . $data;
    }
}
