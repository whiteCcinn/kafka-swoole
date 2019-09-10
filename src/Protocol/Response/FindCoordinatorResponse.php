<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class FindCoordinatorResponse extends AbstractRequest
{
    /**
     * The error code, or 0 if there was no error.
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * The node id.
     * @var Int32 $nodeId
     */
    private $nodeId;

    /**
     * The host name.
     * @var String16 $host
     */
    private $host;

    /**
     * The port.
     * @var Int32 $port
     */
    private $port;

    /**
     * @return Int16
     */
    public function getErrorCode(): Int16
    {
        return $this->errorCode;
    }

    /**
     * @param Int16 $errorCode
     *
     * @return FindCoordinatorResponse
     */
    public function setErrorCode(Int16 $errorCode): FindCoordinatorResponse
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getNodeId(): Int32
    {
        return $this->nodeId;
    }

    /**
     * @param Int32 $nodeId
     *
     * @return FindCoordinatorResponse
     */
    public function setNodeId(Int32 $nodeId): FindCoordinatorResponse
    {
        $this->nodeId = $nodeId;

        return $this;
    }

    /**
     * @return String16
     */
    public function getHost(): String16
    {
        return $this->host;
    }

    /**
     * @param String16 $host
     *
     * @return FindCoordinatorResponse
     */
    public function setHost(String16 $host): FindCoordinatorResponse
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getPort(): Int32
    {
        return $this->port;
    }

    /**
     * @param Int32 $port
     *
     * @return FindCoordinatorResponse
     */
    public function setPort(Int32 $port): FindCoordinatorResponse
    {
        $this->port = $port;

        return $this;
    }
}
