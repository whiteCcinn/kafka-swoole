<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Protocol\Response\Common\ResponseHeader;
use \ReflectionClass;

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

    /**
     * @param String $protocol
     *
     * @throws \ReflectionException
     */
    public function unpack(String $protocol)
    {
        $refClass = new ReflectionClass(static::class);

        $refProperties = $this->getProperties($refClass);

        var_dump($refProperties);exit;
    }

    /**
     * @param ReflectionClass $refClass
     *
     * @return array
     */
    private function getProperties(ReflectionClass $refClass): array
    {
        $commonRefProperties = $refClass->getProperties(ReflectionProperty::IS_PROTECTED);
        $refProperties = $refClass->getProperties(ReflectionProperty::IS_PRIVATE);
        if (!empty($commonRefProperties)) {
            [$requestHeader, $size] = $commonRefProperties;
            array_unshift($refProperties, $requestHeader);
            array_push($refProperties, $size);
        }

        return $refProperties;
    }
}
