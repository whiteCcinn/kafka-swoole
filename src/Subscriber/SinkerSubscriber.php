<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use App\App;
use App\Controller\SinkerController;
use Kafka\ClientKafka;
use Kafka\Config\CommonConfig;
use Kafka\Enum\ClientApiModeEnum;
use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolPartitionAssignmentStrategyEnum;
use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Event\CoreLogicAfterEvent;
use Kafka\Event\CoreLogicBeforeEvent;
use Kafka\Event\CoreLogicEvent;
use Kafka\Event\FetchMessageEvent;
use Kafka\Event\HeartbeatEvent;
use Kafka\Event\OffsetCommitEvent;
use Kafka\Event\SinkerEvent;
use Kafka\Exception\ClientException;
use Kafka\Exception\RequestException\FetchRequestException;
use Kafka\Exception\RequestException\FindCoordinatorRequestException;
use Kafka\Exception\RequestException\HeartbeatRequestException;
use Kafka\Exception\RequestException\JoinGroupRequestException;
use Kafka\Exception\RequestException\ListOffsetsRequestException;
use Kafka\Exception\RequestException\OffsetCommitRequestException;
use Kafka\Exception\RequestException\OffsetFetchRequestException;
use Kafka\Exception\RequestException\SyncGroupRequestException;
use Kafka\Kafka;
use Kafka\Protocol\Request\Fetch\PartitionsFetch;
use Kafka\Protocol\Request\Fetch\TopicsFetch;
use Kafka\Protocol\Request\FetchRequest;
use Kafka\Protocol\Request\FindCoordinatorRequest;
use Kafka\Protocol\Request\HeartbeatRequest;
use Kafka\Protocol\Request\JoinGroup\ProtocolMetadataJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolNameJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolsJoinGroup;
use Kafka\Protocol\Request\JoinGroup\TopicJoinGroup;
use Kafka\Protocol\Request\JoinGroupRequest;
use Kafka\Protocol\Request\ListOffsets\PartitionsListsOffsets;
use Kafka\Protocol\Request\ListOffsets\TopicsListsOffsets;
use Kafka\Protocol\Request\ListOffsetsRequest;
use Kafka\Protocol\Request\OffsetCommit\PartitionsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommit\TopicsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommitRequest;
use Kafka\Protocol\Request\OffsetFetch\PartitionsOffsetFetch;
use Kafka\Protocol\Request\OffsetFetch\TopicsOffsetFetch;
use Kafka\Protocol\Request\OffsetFetchRequest;
use Kafka\Protocol\Request\SyncGroup\GroupAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\MemberAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\PartitionAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroupRequest;
use Kafka\Protocol\Response\FetchResponse;
use Kafka\Protocol\Response\FindCoordinatorResponse;
use Kafka\Protocol\Response\HeartbeatResponse;
use Kafka\Protocol\Response\JoinGroupResponse;
use Kafka\Protocol\Response\ListOffsetsResponse;
use Kafka\Protocol\Response\OffsetCommitResponse;
use Kafka\Protocol\Response\OffsetFetchResponse;
use Kafka\Protocol\Response\SyncGroupResponse;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Socket\Socket;
use Kafka\Storage\RedisStorage;
use Kafka\Storage\StorageAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SinkerSubscriber
 *
 * @package Kafka\Subscriber
 */
class SinkerSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SinkerEvent::NAME => 'onSinker',
        ];
    }

    /**
     * @param SinkerEvent $event
     *
     * @throws \Exception
     */
    public function onSinker(SinkerEvent $event): void
    {
        /** @var StorageAdapter $adapter */
        $adapter = StorageAdapter::getInstance();
        /** @var RedisStorage $storage */
        $storage = RedisStorage::getInstance();
        $adapter->setAdaptee($storage);
        while (true) {
            $messages = $storage->pop();
            (new SinkerController())->handler($messages);
        }
    }
}