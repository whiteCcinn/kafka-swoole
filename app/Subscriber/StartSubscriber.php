<?php
declare(strict_types=1);

namespace App\Subscriber;

use App\Event\StartAfterEvent;
use App\Event\StartBeforeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class StartSubscriber
 *
 * @package App\Subscriber
 */
class StartSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            StartBeforeEvent::NAME => 'onStartBefore',
            StartAfterEvent::NAME  => 'onStartAfter',
        ];
    }

    public function onStartBefore(): void
    {
        // do something
    }

    public function onStartAfter(): void
    {
        // do something
    }
}