<?php
declare(strict_types=1);

namespace App\Subscriber;

use App\Handler\HighLevelHandler;
use App\Handler\LowLevelHandler;
use Kafka\Enum\ClientApiModeEnum;
use Kafka\Enum\MessageStorageEnum;
use Kafka\Event\FetchMessageEvent;
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

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FetchMessageEvent::NAME => 'onFetchMessage',
        ];
    }

    public function onFetchMessage(FetchMessageEvent $event)
    {
        if ($this->messageStorage === null) {
            $this->messageStorage = env('KAFKA_MESSAGE_STORAGE');
        }
        if ($this->mode === null) {
            $this->mode = env('KAFKA_CLIENT_API_MODE');
        }
        if ($this->handler === null) {
            if ($this->mode ===  ClientApiModeEnum::getTextByCode(ClientApiModeEnum::HIGH_LEVEL)) {
                $this->handler = new HighLevelHandler();
            } else {
                $this->handler = new LowLevelHandler();
            }
        }
        switch ($this->messageStorage) {
            case MessageStorageEnum::DIRECTLY:
                if ($this->mode === ClientApiModeEnum::getTextByCode(ClientApiModeEnum::HIGH_LEVEL)) {
                    /** @var HighLevelHandler $handler */
                    $handler = $this->handler;
                    $handler->handler($event->getMessage());
                } else {
                    /** @var LowLevelHandler $handler */
                    $handler = $this->handler;
                    $handler->handler($event->getOffset(), $event->getMessage());
                }
                break;
            case MessageStorageEnum::FILE:
                break;
            case MessageStorageEnum::REDIS:
                break;
            default:
                //
        }
    }
}