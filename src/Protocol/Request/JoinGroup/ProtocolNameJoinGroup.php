<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\JoinGroup;

use Kafka\Enum\ProtocolPartitionAssignmentStrategyEnum;
use Kafka\Protocol\Type\String16;

class ProtocolNameJoinGroup
{
    /**
     * @var String16 $assignmentStrategy
     */
    private $assignmentStrategy;

    /**
     * @return String16
     */
    public function getAssignmentStrategy(): String16
    {
        return $this->assignmentStrategy;
    }

    /**
     * @param String16 $assignmentStrategy
     *
     * @return ProtocolNameJoinGroup
     */
    public function setAssignmentStrategy(String16 $assignmentStrategy): ProtocolNameJoinGroup
    {
        // The default sticky partition reduces the overhead of Rebalance
        if (!in_array($assignmentStrategy->getValue(), ProtocolPartitionAssignmentStrategyEnum::getAllText())) {
            $assignmentStrategy->setValue(ProtocolPartitionAssignmentStrategyEnum::getTextByCode(ProtocolPartitionAssignmentStrategyEnum::STICKY_ASSIGNOR));
        }
        $this->assignmentStrategy = $assignmentStrategy;

        return $this;
    }
}
