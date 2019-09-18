<?php

namespace Kafka\I18N;

use Kafka\Enum\I18NEnum;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class I18N
{
    /** @var string */
    private const MODULE_NAME = 'I18N';

    /** @var string */
    private static $file = 'yaml';

    /**
     * @var Translator $instance
     */
    private static $instance;

    /**
     * @return Translator
     */
    public static function getInstance(): Translator
    {
        if (!self::$instance instanceof I18N) {
            $translator = new Translator(env('APP_LANGUGE'));
            $translator->addLoader(self::$file, new YamlFileLoader());
            self::$instance = $translator;

            self::addZhResource();
            self::addEnResource();
        }

        return self::$instance;
    }


    /**
     * add zh_CN Resource
     */
    private static function addZhResource(): void
    {
        self::$instance->addResource(
            self::$file,
            KAFKA_SWOOLE_SRC . DIRECTORY_SEPARATOR .
            self::MODULE_NAME . DIRECTORY_SEPARATOR .
            I18NEnum::getTextByCode(I18NEnum::ZH_TRANSLATOR),
            I18NEnum::ZH_TRANSLATOR
        );
    }

    /**
     * add en_US Resource
     */
    private static function addEnResource(): void
    {
        self::$instance->addResource(
            self::$file,
            KAFKA_SWOOLE_SRC . DIRECTORY_SEPARATOR .
            self::MODULE_NAME . DIRECTORY_SEPARATOR .
            I18NEnum::getTextByCode(I18NEnum::EN_TRANSLATOR),
            I18NEnum::EN_TRANSLATOR
        );
    }
}