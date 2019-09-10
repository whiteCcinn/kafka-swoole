<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Common;

use \Kafka\Protocol\Type\Int32;
use \Kafka\Protocol\Response\MetadataResponse;
use \Kafka\Protocol\Response\FetchResponse;
use \Kafka\Protocol\Response\OffsetCommitResponse;
use \Kafka\Protocol\Response\OffsetFetchResponse;
use \Kafka\Protocol\Response\ListOffsetsResponse;
use \Kafka\Protocol\Response\ProduceResponse;


/**
 * Class ResponseMessage
 */
class ResponseMessage
{
    /**
     * @var Int32 $correlationId
     */
    private $correlationId;

    /**
     * @var MetadataResponse|FetchResponse|OffsetCommitResponse|OffsetFetchResponse|ListOffsetsResponse|ProduceResponse
     *      $ResponseMessage
     */
    private $ResponseMessage;

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
     * @return ResponseMessage
     */
    public function setCorrelationId(Int32 $correlationId): ResponseMessage
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    /**
     * @return FetchResponse|MetadataResponse|OffsetCommitResponse|OffsetFetchResponse|ListOffsetsResponse|ProduceResponse
     */
    public function getResponseMessage()
    {
        return $this->ResponseMessage;
    }

    /**
     * @param FetchResponse|MetadataResponse|OffsetCommitResponse|OffsetFetchResponse|ListOffsetsResponse|ProduceResponse $ResponseMessage
     *
     * @return ResponseMessage
     */
    public function setResponseMessage($ResponseMessage)
    {
        $this->ResponseMessage = $ResponseMessage;

        return $this;
    }
}