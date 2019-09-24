<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\JoinGroup;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Protocol\CommonRequest;

class ProtocolsJoinGroup
{
    /**
     * @var ProtocolNameJoinGroup $name
     */
    private $name;

    /**
     * @var ProtocolMetadataJoinGroup $metadata
     */
    private $metadata;

    /**
     * @return ProtocolNameJoinGroup
     */
    public function getName(): ProtocolNameJoinGroup
    {
        return $this->name;
    }

    /**
     * @param ProtocolNameJoinGroup $name
     *
     * @return ProtocolsJoinGroup
     */
    public function setName(ProtocolNameJoinGroup $name): ProtocolsJoinGroup
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ProtocolMetadataJoinGroup
     */
    public function getMetadata(): ProtocolMetadataJoinGroup
    {
        return $this->metadata;
    }

    /**
     * @param ProtocolMetadataJoinGroup $metadata
     *
     * @return ProtocolsJoinGroup
     */
    public function setMetadata(ProtocolMetadataJoinGroup $metadata): ProtocolsJoinGroup
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param $protocol
     *
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function onMetadata(&$protocol)
    {
        $commentRequest = new CommonRequest();
        $data = $commentRequest->packProtocol(ProtocolMetadataJoinGroup::class, $this->metadata);
        $protocol .= pack(ProtocolTypeEnum::getTextByCode(ProtocolTypeEnum::B32), strlen($data)) . $data;
    }
}
