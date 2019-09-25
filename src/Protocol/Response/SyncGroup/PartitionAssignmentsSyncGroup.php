<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\SyncGroup;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class PartitionAssignmentsSyncGroup
{
    use ToArrayTrait;
    /**
     * @var String16 $topic
     */
    private $topic;

    /**
     * @var Int32[] $partition
     */
    private $partition;

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
     * @return PartitionAssignmentsSyncGroup
     */
    public function setTopic(String16 $topic): PartitionAssignmentsSyncGroup
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Int32[]
     */
    public function getPartition(): array
    {
        return $this->partition;
    }

    /**
     * @param Int32[] $partition
     *
     * @return PartitionAssignmentsSyncGroup
     */
    public function setPartition(array $partition): PartitionAssignmentsSyncGroup
    {
        $this->partition = $partition;

        return $this;
    }
}
