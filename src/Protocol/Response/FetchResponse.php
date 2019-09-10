<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Response\Fetch\ResponsesFetch;

class FetchResponse extends AbstractRequest
{
    /**
     * null
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
     * @return FetchResponse
     */
    public function setResponses(array $responses): FetchResponse
    {
        $this->responses = $responses;

        return $this;
    }
}
