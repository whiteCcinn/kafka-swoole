<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\Produce\ResponsesProduce;

class ProduceResponse extends AbstractResponse
{
    /**
     * @var ResponsesProduce[] $responses
     */
    private $responses;

    /**
     * @return ResponsesProduce[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param ResponsesProduce[] $responses
     *
     * @return ProduceResponse
     */
    public function setResponses(array $responses): ProduceResponse
    {
        $this->responses = $responses;

        return $this;
    }
}
