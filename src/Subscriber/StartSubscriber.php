<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use Kafka\Event\StartAfterEvent;
use Kafka\Event\StartBeforeEvent;
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
        $socket = new \Co\Socket(AF_INET, SOCK_STREAM, 0);

        go(function () use ($socket) {
            // 主进程逻辑（监控子进程/控制进程数）
            $retval = $socket->connect('mkafka1', 9092);
            while ($retval) {
                if (empty($data)) {
                    $socket->close();
                    break;
                }
                \Co::sleep(10.0);
            }
            var_dump($retval, $socket->errCode);
        });
    }

    public function onStartAfter(): void
    {
        // do something
    }
}