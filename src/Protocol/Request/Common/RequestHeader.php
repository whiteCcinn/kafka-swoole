<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Common;

use \Kafka\Protocol\Type\Int16;
use \Kafka\Protocol\Type\Int32;
use \Kafka\Protocol\Type\String16;

/**
 * Class RequestHeader
 *
 * @package Kafka\Protocol\Request\Common
 */
class RequestHeader
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

    /**
     * @return Int16
     */
    public function getApiKey(): Int16
    {
        return $this->apiKey;
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
     * @return RequestHeader
     */
    public function setApiVersion(Int16 $apiVersion): RequestHeader
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
     * @return RequestHeader
     */
    public function setCorrelationId(Int32 $correlationId): RequestHeader
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
     * @return RequestHeader
     */
    public function setClientId(String16 $clientId): RequestHeader
    {
        $this->clientId = $clientId;

        return $this;
    }
}