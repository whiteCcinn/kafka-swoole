<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use App\App;
use Kafka\Command\StartCommand;
use Kafka\Event\BootAfterEvent;
use Kafka\Event\BootBeforeEvent;
use Kafka\Event\StartBeforeEvent;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class BootSubscriber
 *
 * @package Kafka\Subscriber
 */
class BootSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BootBeforeEvent::NAME => 'onBootBefore',
            BootAfterEvent::NAME  => 'onBootAfter',
        ];
    }

    public function onBootBefore(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(KAFKA_SWOOLE_ROOT . DIRECTORY_SEPARATOR . '.env');

        APP::$translator = \Kafka\I18N\I18N::getInstance();
//        set_exception_handler([BaseException::class, BaseException::$exception_function_name]);
    }

    /**
     * @throws \Exception
     */
    public function onBootAfter(): void
    {
        // start App
        App::boot();
        $this->registerSubscriber();
        $this->registerCommand();
    }

    /**
     * @throws \Exception
     */
    private function registerCommand(): void
    {
        App::$application->add(new StartCommand());
        App::$application->run();
    }

    private function registerSubscriber(): void
    {
        App::$dispatcher->addSubscriber(new StartSubscriber());
    }
}