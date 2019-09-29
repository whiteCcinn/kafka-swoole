<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class OffsetResetEnum
 *
 * @package Kafka\Enum
 */
class OffsetResetEnum extends AbstractEnum
{
    /**
     * @message("smallest")
     */
    public const SMALLEST = 0;

    /**
     * @message("largest")
     */
    public const LARGEST = 1;
}