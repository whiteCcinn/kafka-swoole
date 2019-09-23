<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Fetch;

use Kafka\Protocol\TraitStructure\ToArrayTrait;

class PartitionResponsesFetch
{
    use ToArrayTrait;

    /**
     * @var PartitionHeaderFetch $partitionHeader
     */
    private $partitionHeader;

    /**
     * null
     *
     * @var MessageSetFetch[] $recordSet
     */
    private $recordSet;

    /**
     * @return PartitionHeaderFetch
     */
    public function getPartitionHeader(): PartitionHeaderFetch
    {
        return $this->partitionHeader;
    }

    /**
     * @param PartitionHeaderFetch $partitionHeader
     *
     * @return PartitionResponsesFetch
     */
    public function setPartitionHeader(PartitionHeaderFetch $partitionHeader): PartitionResponsesFetch
    {
        $this->partitionHeader = $partitionHeader;

        return $this;
    }

    /**
     * @return MessageSetFetch[]
     */
    public function getRecordSet(): array
    {
        return $this->recordSet;
    }

    /**
     * @param MessageSetFetch[] $recordSet
     *
     * @return PartitionResponsesFetch
     */
    public function setRecordSet(array $recordSet): PartitionResponsesFetch
    {
        $this->recordSet = $recordSet;

        return $this;
    }
}
