<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\CreateTopics;

use Kafka\Protocol\Type\Int32;

class AssignmentsCreateTopics
{
    /**
     * The partition index.
     *
     * @var Int32 $partitionIndex
     */
    private $partitionIndex;

    /**
     * The brokers to place the partition on.
     *
     * @var Int32[] $brokerIds
     */
    private $brokerIds;

    /**
     * @return Int32
     */
    public function getPartitionIndex(): Int32
    {
        return $this->partitionIndex;
    }

    /**
     * @param Int32 $partitionIndex
     *
     * @return AssignmentsCreateTopics
     */
    public function setPartitionIndex(Int32 $partitionIndex): AssignmentsCreateTopics
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }

    /**
     * @return Int32[]
     */
    public function getBrokerIds(): array
    {
        return $this->brokerIds;
    }

    /**
     * @param Int32[] $brokerIds
     *
     * @return AssignmentsCreateTopics
     */
    public function setBrokerIds(array $brokerIds): AssignmentsCreateTopics
    {
        $this->brokerIds = $brokerIds;

        return $this;
    }
}