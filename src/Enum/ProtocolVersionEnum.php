<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class ProtocolVersionEnum
 *
 * @package Kafka\Enum
 */
class ProtocolVersionEnum extends AbstractEnum
{
    /**
     * @message("API_VERSION is 0")
     */
    public const API_VERSION_0 = 0;

    /**
     * @message("API_VERSION is 1")
     */
    public const API_VERSION_1 = 1;
}