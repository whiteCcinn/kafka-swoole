<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use Kafka\Event\StartAfterEvent;
use Kafka\Event\StartBeforeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Kafka\Manager\MetadataManager;
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
        $metadataManager = new MetadataManager();
        $metadataManager->registerMetadataInfo();
    }

    public function onStartAfter(): void
    {
        // do something
    }
}