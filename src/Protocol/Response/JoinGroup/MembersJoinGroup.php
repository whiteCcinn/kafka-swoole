<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class MembersJoinGroup
{
    /**
     * The group member ID.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The group member metadata.
     *
     * @var Bytes32 $metadata
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
     * @return Bytes32
     */
    public function getMetadata(): Bytes32
    {
        return $this->metadata;
    }

    /**
     * @param Bytes32 $metadata
     *
     * @return MembersJoinGroup
     */
    public function setMetadata(Bytes32 $metadata): MembersJoinGroup
    {
        $this->metadata = $metadata;

        return $this;
    }
}
