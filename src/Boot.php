<?php
declare(strict_types=1);

namespace Kafka;

use App\Command\StartCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;
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
        $dotenv->load(KAFKA_SWOOLE_ROOT . DIRECTORY_SEPARATOR . '.env');
    }

    /**
     * @throws \Exception
     */
    private static function console()
    {
        $application = new Application();

        $application->add(new StartCommand());

        $application->run();
    }
}