<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\String16;

class MembersJoinGroup
{
    use ToArrayTrait;

    /**
     * The group member ID.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The group member metadata.
     *
     * @var ProtocolMetadataJoinGroup $metadata
     */
    private $metadata;

    /**
     * @return String16
     */
    public function getMemberId(): String16
    {
        return $this->memberId;
    }

    /**
     * @param String16 $memberId
     *
     * @return MembersJoinGroup
     */
    public function setMemberId(String16 $memberId): MembersJoinGroup
    {
        $this->memberId = $memberId;

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
     * @return MembersJoinGroup
     */
    public function setMetadata(ProtocolMetadataJoinGroup $metadata): MembersJoinGroup
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param $protocol
     *
     * @return bool
     */
    public function onMetadata(&$protocol): bool
    {
        $data = unpack(ProtocolTypeEnum::getTextByCode(ProtocolTypeEnum::B32),
            substr($protocol, 0, ProtocolTypeEnum::B32));
        $data = is_array($data) ? array_shift($data) : $data;
        $length = $data;
        $protocol = substr($protocol, ProtocolTypeEnum::B32, $length);

        return false;
    }
}
