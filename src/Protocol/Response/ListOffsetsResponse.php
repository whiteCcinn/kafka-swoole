<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\ListOffsets\ResponsesListOffsets;
use Kafka\Protocol\TraitStructure\ToArrayTrait;

class ListOffsetsResponse extends AbstractResponse
{
    use ToArrayTrait;
    /**
     * @var ResponsesListOffsets[] $responses
     */
    private $responses;

    /**
     * @return ResponsesListOffsets[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param ResponsesListOffsets[] $responses
     *
     * @return ListOffsetsResponse
     */
    public function setResponses(array $responses): ListOffsetsResponse
    {
        $this->responses = $responses;

        return $this;
    }
}
