<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use Kafka\ClientKafka;
use Kafka\Enum\ClientApiModeEnum;
use Kafka\Event\MessageConsumedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class StepSubscriber
 *
 * @package Kafka\Subscriber
 */
class StepSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MessageConsumedEvent::NAME => 'onMessageConsumed'
        ];
    }

    /**
     * @param MessageConsumedEvent $event
     */
    public function onMessageConsumed(MessageConsumedEvent $event)
    {
        if ($event->getType() === ClientApiModeEnum::HIGH_LEVEL) {
            $topic = $event->getTopic();
            $partition = $event->getPartition();
            $offset = $event->getOffset();
            ClientKafka::getInstance()->setTopicPartitionOffset($topic, $partition, $offset);
        }
    }
}