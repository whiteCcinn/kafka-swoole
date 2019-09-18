<?php

namespace App;

use App\Command\StartCommand;
use App\Subscriber\StartSubscriber;
use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Translation\Translator;

class App
{
    private static $once = false;

    /**
     * @var EventDispatcher $dispatcher
     */
    public static $dispatcher;

    /**
     * @var Application $application
     */
    public static $application;

    /**
     * @var Translator $translator
     */
    public static $translator;

    /**
     * @throws \Exception
     */
    public static function boot()
    {
        if (!self::$once) {
            self::registerAppCommand();
            self::registerAppSubscriber();
            self::changeOnce();
        }
    }

    private static function changeOnce(): void
    {
        self::$once = !self::$once;
    }

    private static function registerAppCommand(): void
    {
        self::$application->add(new StartCommand());
        self::$application->run();
    }

    private static function registerAppSubscriber(): void
    {
        self::$dispatcher->addSubscriber(new StartSubscriber());
    }
}