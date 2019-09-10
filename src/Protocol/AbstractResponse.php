<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Protocol\Response\Common\ResponseHeader;

abstract class AbstractResponse extends AbstractRequestOrResponse
{
    /**
     * @var ResponseHeader $requestHeader
     */
    private $responseHeader;

    /**
     * @return ResponseHeader
     */
    public function getResponseHeader(): ResponseHeader
    {
        return $this->responseHeader;
    }

    /**
     * @param ResponseHeader $responseHeader
     *
     * @return AbstractResponse
     */
    public function setResponseHeader(ResponseHeader $responseHeader): AbstractResponse
    {
        $this->responseHeader = $responseHeader;

        return $this;
    }
}
