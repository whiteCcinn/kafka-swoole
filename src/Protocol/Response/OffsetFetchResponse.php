<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\OffsetFetch\ResponsesOffsetFetch;
use Kafka\Protocol\TraitStructure\ToArrayTrait;


class OffsetFetchResponse extends AbstractResponse
{
    use ToArrayTrait;

    /**
     * Responses by topic for fetched offsets
     *
     * @var ResponsesOffsetFetch[] $responses
     */
    private $responses;

    /**
     * @return ResponsesOffsetFetch[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param ResponsesOffsetFetch[] $responses
     *
     * @return OffsetFetchResponse
     */
    public function setResponses(array $responses): OffsetFetchResponse
    {
        $this->responses = $responses;

        return $this;
    }
}
