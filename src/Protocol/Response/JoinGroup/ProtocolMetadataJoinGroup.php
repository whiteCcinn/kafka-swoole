<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\JoinGroup;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;

class ProtocolMetadataJoinGroup
{
    use ToArrayTrait;

    /**
     * @var Int16 $version
     */
    private $version;

    /**
     * @var TopicJoinGroup[] $subscription
     */
    private $subscription;

    /**
     * @var Bytes32 $userData
     */
    private $userData;

    /**
     * @return Int16
     */
    public function getVersion(): Int16
    {
        return $this->version;
    }

    /**
     * @param Int16 $version
     *
     * @return ProtocolMetadataJoinGroup
     */
    public function setVersion(Int16 $version): ProtocolMetadataJoinGroup
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param mixed $subscription
     *
     * @return ProtocolMetadataJoinGroup
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return Bytes32
     */
    public function getUserData(): Bytes32
    {
        return $this->userData;
    }

    /**
     * @param Bytes32 $userData
     *
     * @return ProtocolMetadataJoinGroup
     */
    public function setUserData(Bytes32 $userData): ProtocolMetadataJoinGroup
    {
        $this->userData = $userData;

        return $this;
    }
}
