<?php
declare(strict_types=1);

namespace App\Subscriber;

use App\Handler\HighLevelHandler;
use App\Handler\LowLevelHandler;
use Kafka\Enum\ClientApiModeEnum;
use Kafka\Enum\MessageReliabilityEnum;
use Kafka\Enum\MessageStorageEnum;
use Kafka\Event\FetchMessageEvent;
use Kafka\Event\FetchMessagesEvent;
use Kafka\Event\MessageConsumedEvent;
use Kafka\Storage\RedisStorage;
use Kafka\Storage\StorageAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ApiSubscriber
 *
 * @package App\Subscriber
 */
class ApiSubscriber implements EventSubscriberInterface
{
    /**
     * @var HighLevelHandler | LowLevelHandler $handler
     */
    private $handler;

    private $messageStorage;

    private $mode;

    private $messageReliability;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FetchMessagesEvent::NAME => 'onFetchMessages',
        ];
    }

    public function onFetchMessages(FetchMessagesEvent $event)
    {
        $messages = $event->getMessages();
        if ($this->messageStorage === null) {
            $this->messageStorage = env('KAFKA_MESSAGE_STORAGE');
        }
        if ($this->mode === null) {
            $this->mode = env('KAFKA_CLIENT_API_MODE');
        }
        if ($this->mode === null) {
            $this->messageReliability = env('KAFKA_MESSAGE_RELIABILITY');
        }
        if ($this->handler === null) {
            if ($this->mode === ClientApiModeEnum::getTextByCode(ClientApiModeEnum::HIGH_LEVEL)) {
                $this->handler = new HighLevelHandler();
            } else {
                $this->handler = new LowLevelHandler();
            }
        }
        switch (MessageStorageEnum::getCodeByText($this->messageStorage)) {
            case MessageStorageEnum::DIRECTLY:
                if ($this->mode === ClientApiModeEnum::getTextByCode(ClientApiModeEnum::HIGH_LEVEL)) {
                    /** @var HighLevelHandler $handler */
                    $handler = $this->handler;
                    foreach ($messages as $item) {
                        ['topic' => $topic, 'partition' => $partition, 'offset' => $offset, 'message' => $message] = $item;
                        $handler->handler($topic, $partition, $offset, $message);
                    }
                } else {
                    /** @var LowLevelHandler $handler */
                    $handler = $this->handler;
                    $topic = '';
                    $partition = $offset = 0;
                    foreach ($messages as $item) {
                        ['topic' => $topic, 'partition' => $partition, 'offset' => $offset, 'message' => $message] = $item;
                        $handler->handler($topic, $partition, $offset, $message);
                        if ($this->messageReliability === MessageReliabilityEnum::getTextByCode(MessageReliabilityEnum::HIGH)) {
                            dispatch(
                                new MessageConsumedEvent(
                                    ClientApiModeEnum::LOW_LEVEL,
                                    $topic,
                                    $partition,
                                    $offset
                                ),
                                MessageConsumedEvent::NAME
                            );
                        }
                    }
                    if ($this->messageReliability === MessageReliabilityEnum::getTextByCode(MessageReliabilityEnum::LOW)) {
                        dispatch(
                            new MessageConsumedEvent(
                                ClientApiModeEnum::LOW_LEVEL,
                                $topic,
                                $partition,
                                $offset
                            ),
                            MessageConsumedEvent::NAME
                        );
                    }
                }
                break;
            case MessageStorageEnum::FILE:
                break;
            case MessageStorageEnum::REDIS:
                /** @var StorageAdapter $adapter */
                $adapter = StorageAdapter::getInstance();
                /** @var RedisStorage $storage */
                $storage = RedisStorage::getInstance();
                $adapter->setAdaptee($storage);
                $pushMessages = array_column($messages, 'message');
                // unreliable...
                $acks = $adapter->push($pushMessages);
                ['topic' => $topic, 'partition' => $partition, 'offset' => $offset] = end($messages);

                dispatch(
                    new MessageConsumedEvent(
                        ClientApiModeEnum::LOW_LEVEL,
                        $topic,
                        $partition,
                        $offset
                    ),
                    MessageConsumedEvent::NAME
                );
                break;
            default:
                //
        }
    }
}
