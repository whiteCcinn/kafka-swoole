<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\SyncGroup;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;

class MemberAssignmentsSyncGroup
{
    /**
     * @var Int16 $version
     */
    private $version;

    /**
     * @var PartitionAssignmentsSyncGroup[] $partitionAssignment
     */
    private $partitionAssignment;

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
     * @return MemberAssignmentsSyncGroup
     */
    public function setVersion(Int16 $version): MemberAssignmentsSyncGroup
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return PartitionAssignmentsSyncGroup[]
     */
    public function getPartitionAssignment(): array
    {
        return $this->partitionAssignment;
    }

    /**
     * @param PartitionAssignmentsSyncGroup[] $partitionAssignment
     *
     * @return MemberAssignmentsSyncGroup
     */
    public function setPartitionAssignment(array $partitionAssignment): MemberAssignmentsSyncGroup
    {
        $this->partitionAssignment = $partitionAssignment;

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
     * @return MemberAssignmentsSyncGroup
     */
    public function setUserData(Bytes32 $userData): MemberAssignmentsSyncGroup
    {
        $this->userData = $userData;

        return $this;
    }
}
