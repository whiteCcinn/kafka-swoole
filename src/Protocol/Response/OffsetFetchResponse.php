<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Response\Fetch\ResponsesFetch;


class OffsetFetchResponse extends AbstractRequest
{
    /**
     * Responses by topic for fetched offsets
     *
     * @var ResponsesFetch[] $responses
     */
    private $responses;

    /**
     * @return ResponsesFetch[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param ResponsesFetch[] $responses
     *
     * @return OffsetFetchResponse
     */
    public function setResponses(array $responses): OffsetFetchResponse
    {
        $this->responses = $responses;

        return $this;
    }
}
