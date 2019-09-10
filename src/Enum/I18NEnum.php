<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class I18NEnum
 *
 * @package Kafka\Enum
 */
class I18NEnum extends AbstractEnum
{
    /**
     * @message("system.zh.yaml")
     */
    public const ZH_TRANSLATOR = 'zh_CN';

    /**
     * @message("system.en.yaml")
     */
    public const EN_TRANSLATOR = 'en_US';
}