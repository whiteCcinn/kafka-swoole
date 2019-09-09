<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\String16;

class TopicsOffsetCommit
{
    /**
     * 	The topic name.
     *
     * @var String16 $name
     */
    private $name;

    /**
     * Each partition to commit offsets for.
     *
     * @var PartitionsOffsetCommit[] $partitions
     */
    private $partitions;

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
     * @return TopicsOffsetCommit
     */
    public function setName(String16 $name): TopicsOffsetCommit
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return PartitionsOffsetCommit[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionsOffsetCommit[] $partitions
     *
     * @return TopicsOffsetCommit
     */
    public function setPartitions(array $partitions): TopicsOffsetCommit
    {
        $this->partitions = $partitions;

        return $this;
    }
}
