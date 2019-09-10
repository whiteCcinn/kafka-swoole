<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Response\ListOffsets\ResponsesListOffsets;

class ListOffsetsResponse extends AbstractRequest
{
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
