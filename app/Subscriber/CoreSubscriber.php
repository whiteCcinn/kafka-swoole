<?php
declare(strict_types=1);

namespace App\Subscriber;

use Kafka\Event\SinkerOtherEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ApiSubscriber
 *
 * @package App\Subscriber
 */
class CoreSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SinkerOtherEvent::NAME => 'onSinkerOther'
        ];
    }

    public function onSinkerOther(SinkerOtherEvent $event)
    {
        // Do something parallel to the sink task and remember to release control
    }
}