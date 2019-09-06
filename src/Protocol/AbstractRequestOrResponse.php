<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Protocol\Type\Int32;

class AbstractRequestOrResponse
{
    /** @var Int32 $size */
    private $size;

    /** @var RequestMessage $requestMessage */
    private $requestMessage;

    /**
     * @return Int32
     */
    public function getSize(): Int32
    {
        return $this->size;
    }

    /**
     * @param Int32 $size
     *
     * @return AbstractRequestOrResponse
     */
    public function setSize(Int32 $size): AbstractRequestOrResponse
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return RequestMessage
     */
    public function getRequestMessage(): RequestMessage
    {
        return $this->requestMessage;
    }

    /**
     * @param RequestMessage $requestMessage
     *
     * @return AbstractRequestOrResponse
     */
    public function setRequestMessage(RequestMessage $requestMessage): AbstractRequestOrResponse
    {
        $this->requestMessage = $requestMessage;

        return $this;
    }
}
