<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\Fetch\ResponsesFetch;
use Kafka\Protocol\TraitStructure\ToArrayTrait;


class OffsetFetchResponse extends AbstractResponse
{
    use ToArrayTrait;

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
