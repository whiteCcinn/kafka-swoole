<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class ClientApiModeEnum
 *
 * @package Kafka\Enum
 */
class ClientApiModeEnum extends AbstractEnum
{
    /**
     * @message("HIGH_LEVEL")
     */
    public const HIGH_LEVEL = 0;

    /**
     * @message("LOW_LEVEL")
     */
    public const LOW_LEVEL = 1;
}