<?php
declare(strict_types=1);

namespace Kafka;

use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Translation\Translator;
use Kafka\Exceptions\BaseException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function set_exception_handler;

/**
 * Class Boot
 *
 * @package Kafka
 */
class Boot
{
    /**
     * @throws \Exception
     */
    public static function boot()
    {
        $dispatcher = new EventDispatcher();

        self::console();
    }

    /**
     *
     */
    private static function init()
    {
        // set_exception_handler
        set_exception_handler([BaseException::class, BaseException::$exception_function_name]);

        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/.env');
    }

    private static function console()
    {
        $application = new Application();

//        $application->add();

        $application->run();
    }
}