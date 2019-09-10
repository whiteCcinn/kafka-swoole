<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Common;

use \Kafka\Protocol\Type\Int32;

/**
 * Class ResponseHeader
 *
 * @package Kafka\Protocol\Response\Common
 */
class ResponseHeader
{
    /**
     * @var Int32 $correlationId
     */
    private $correlationId;

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
     * @return ResponseHeader
     */
    public function setCorrelationId(Int32 $correlationId): ResponseHeader
    {
        $this->correlationId = $correlationId;

        return $this;
    }
}