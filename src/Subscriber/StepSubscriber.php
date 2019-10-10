<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use Kafka\Api\OffsetCommitApi;
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
     *
     * @throws \Kafka\Exception\RequestException\OffsetCommitRequestException
     */
    public function onMessageConsumed(MessageConsumedEvent $event)
    {
        $topic = $event->getTopic();
        $partition = $event->getPartition();
        $offset = $event->getOffset();
        ClientKafka::getInstance()->setTopicPartitionOffset($topic, $partition, $offset);
        if ($event->getType() === ClientApiModeEnum::LOW_LEVEL) {
            OffsetCommitApi::topicPartitionOffsetCommit($event->getTopic(), $event->getPartition(),
                $event->getOffset());
        }
    }
}