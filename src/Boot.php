<?php
declare(strict_types=1);

namespace Kafka;

use App\App;
use Kafka\Event\BootAfterEvent;
use Kafka\Event\BootBeforeEvent;
use Kafka\Subscriber\BootSubscriber;
use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
        App::$application = new Application();
        App::$dispatcher = new EventDispatcher();
        App::$dispatcher->addSubscriber(new BootSubscriber());
        dispatch(new BootBeforeEvent(), BootBeforeEvent::NAME);
        dispatch(new BootAfterEvent(), BootAfterEvent::NAME);
    }
}