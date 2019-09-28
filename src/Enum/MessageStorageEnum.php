<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class MessageStorageEnum
 *
 * @package Kafka\Enum
 */
class MessageStorageEnum extends AbstractEnum
{
    /**
     * @message("DIRECTLY")
     */
    public const DIRECTLY = 0;

    /**
     * @message("FILE")
     */
    public const FILE = 1;

    /**
     * @message("DIRECTLY")
     */
    public const REDIS = 2;
}