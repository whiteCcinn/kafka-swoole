<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\CreateTopics;


use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class TopicsCreateTopics
{
    /**
     * The topic name.
     *
     * @var String16 $name
     */
    private $name;

    /**
     * The number of partitions to create in the topic, or -1 if we are specifying a manual partition assignment.
     *
     * @var Int32 $numPartitions
     */
    private $numPartitions;

    /**
     * The number of replicas to create for each partition in the topic, or -1 if we are specifying a manual partition assignment.
     *
     * @var Int16 $replicationFactor
     */
    private $replicationFactor;

    /**
     * The manual partition assignment, or the empty array if we are using automatic assignment.
     *
     * @var AssignmentsCreateTopics[] $assignments
     */
    private $assignments;

    /**
     * The custom topic configurations to set.
     *
     * @var
     */
    private $configs;

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
     * @return TopicsCreateTopics
     */
    public function setName(String16 $name): TopicsCreateTopics
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getNumPartitions(): Int32
    {
        return $this->numPartitions;
    }

    /**
     * @param Int32 $numPartitions
     *
     * @return TopicsCreateTopics
     */
    public function setNumPartitions(Int32 $numPartitions): TopicsCreateTopics
    {
        $this->numPartitions = $numPartitions;

        return $this;
    }

    /**
     * @return Int16
     */
    public function getReplicationFactor(): Int16
    {
        return $this->replicationFactor;
    }

    /**
     * @param Int16 $replicationFactor
     *
     * @return TopicsCreateTopics
     */
    public function setReplicationFactor(Int16 $replicationFactor): TopicsCreateTopics
    {
        $this->replicationFactor = $replicationFactor;

        return $this;
    }

    /**
     * @return AssignmentsCreateTopics[]
     */
    public function getAssignments(): array
    {
        return $this->assignments;
    }

    /**
     * @param AssignmentsCreateTopics[] $assignments
     *
     * @return TopicsCreateTopics
     */
    public function setAssignments(array $assignments): TopicsCreateTopics
    {
        $this->assignments = $assignments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @param mixed $configs
     *
     * @return TopicsCreateTopics
     */
    public function setConfigs($configs)
    {
        $this->configs = $configs;

        return $this;
    }
}