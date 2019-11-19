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
            SinkerOtherEvent::NAME          => 'onSinkerOther',
            SetKafkaProcessNameEvent::NAME  => 'onSetKafkaProcessName',
            SetSinkerProcessNameEvent::NAME => 'onSetSinkerProcessName',
            SetMasterProcessNameEvent::NAME => 'onSetMasterProcessName',
        ];
    }

    /**
     * @param SetMasterProcessNameEvent $event
     */
    public function onSetMasterProcessName(SetMasterProcessNameEvent $event): void
    {
        swoole_set_process_name(env('APP_NAME') . '_' . env('APP_ID') . ':master');
    }

    /**
     * @param SetKafkaProcessNameEvent $event
     */
    public function onSetKafkaProcessName(SetKafkaProcessNameEvent $event): void
    {
        $index = $event->getIndex();
        swoole_set_process_name(env('APP_NAME') . '_' . env('APP_ID') . ':kafka_' . $index);
    }

    /**
     * @param SetSinkerProcessNameEvent $event
     */
    public function onSetSinkerProcessName(SetSinkerProcessNameEvent $event): void
    {
        $index = $event->getIndex();
        swoole_set_process_name(env('APP_NAME') . '_' . env('APP_ID') . ':sinker_' . $index);
    }
}
