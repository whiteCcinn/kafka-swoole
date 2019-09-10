<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Protocol\Request\Common\RequestHeader;

abstract class AbstractRequest extends AbstractRequestOrResponse
{
    /**
     * @var RequestHeader $requestHeader
     */
    private $requestHeader;

    /**
     * @return RequestHeader
     */
    public function getRequestHeader(): RequestHeader
    {
        return $this->requestHeader;
    }

    /**
     * @param RequestHeader $requestHeader
     *
     * @return AbstractRequest
     */
    public function setRequestHeader(RequestHeader $requestHeader): AbstractRequest
    {
        $this->requestHeader = $requestHeader;

        return $this;
    }
}
