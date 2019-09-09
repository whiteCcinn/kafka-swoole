<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class ProtocolsJoinGroup
{
    /** @var String16 $name */
    private $name;

    /** @var Bytes32 $metadata */
    private $metadata;

    /**
     * @return String16
     */
    public function getName(): String16
    {
        return $this->name;
    }

    /**
     * @param String16 $name
     *
     * @return ProtocolsJoinGroup
     */
    public function setName(String16 $name): ProtocolsJoinGroup
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Bytes32
     */
    public function getMetadata(): Bytes32
    {
        return $this->metadata;
    }

    /**
     * @param Bytes32 $metadata
     *
     * @return ProtocolsJoinGroup
     */
    public function setMetadata(Bytes32 $metadata): ProtocolsJoinGroup
    {
        $this->metadata = $metadata;

        return $this;
    }
}
