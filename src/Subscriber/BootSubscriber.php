<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use App\App;
use Kafka\Event\BootAfterEvent;
use Kafka\Event\BootBeforeEvent;
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
        // do something
    }

    /**
     * @throws \Exception
     */
    public function onBootAfter(): void
    {
        // start App
        App::boot();
    }
}