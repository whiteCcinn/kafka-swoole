<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Common;

use \Kafka\Protocol\Type\Int16;
use \Kafka\Protocol\Type\Int32;
use \Kafka\Protocol\Type\String16;
use \Kafka\Protocol\Request\MetadataRequest;
use \Kafka\Protocol\Request\FetchRequest;
use \Kafka\Protocol\Request\OffsetCommitRequest;
use \Kafka\Protocol\Request\OffsetFetchRequest;
use \Kafka\Protocol\Request\OffsetRequest;
use \Kafka\Protocol\Request\ProduceRequest;


/**
 * Class RequestMessage
 */
class RequestMessage
{
    /**
     * @var Int16 $apiKey
     */
    private $apiKey;

    /**
     * @var Int16 $apiVersion
     */
    private $apiVersion;

    /**
     * @var Int32 $correlationId
     */
    private $correlationId;

    /**
     * @var String16 $clientId
     */
    private $clientId;

    /** @var MetadataRequest|FetchRequest|OffsetCommitRequest|OffsetFetchRequest|OffsetRequest|ProduceRequest $requestMessage */
    private $requestMessage;

    /**
     * @return Int16
     */
    public function getApiKey(): Int16
    {
        return $this->apiKey;
    }

    /**
     * @param Int16 $apiKey
     *
     * @return RequestMessage
     */
    public function setApiKey(Int16 $apiKey): RequestMessage
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return Int16
     */
    public function getApiVersion(): Int16
    {
        return $this->apiVersion;
    }

    /**
     * @param Int16 $apiVersion
     *
     * @return RequestMessage
     */
    public function setApiVersion(Int16 $apiVersion): RequestMessage
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getCorrelationId(): Int32
    {
        return $this->correlationId;
    }

    /**
     * @param Int32 $correlationId
     *
     * @return RequestMessage
     */
    public function setCorrelationId(Int32 $correlationId): RequestMessage
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    /**
     * @return String16
     */
    public function getClientId(): String16
    {
        return $this->clientId;
    }

    /**
     * @param String16 $clientId
     *
     * @return RequestMessage
     */
    public function setClientId(String16 $clientId): RequestMessage
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return FetchRequest|MetadataRequest|OffsetCommitRequest|OffsetFetchRequest|OffsetRequest|ProduceRequest
     */
    public function getRequestMessage()
    {
        return $this->requestMessage;
    }

    /**
     * @param FetchRequest|MetadataRequest|OffsetCommitRequest|OffsetFetchRequest|OffsetRequest|ProduceRequest $requestMessage
     *
     * @return RequestMessage
     */
    public function setRequestMessage($requestMessage)
    {
        $this->requestMessage = $requestMessage;

        return $this;
    }
}