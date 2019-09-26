<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use Kafka\ClientKafka;
use Kafka\Config\CommonConfig;
use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolPartitionAssignmentStrategyEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Event\CoreLogicAfterEvent;
use Kafka\Event\CoreLogicBeforeEvent;
use Kafka\Event\CoreLogicEvent;
use Kafka\Event\HeartbeatEvent;
use Kafka\Exception\ClientException;
use Kafka\Kafka;
use Kafka\Protocol\Request\FindCoordinatorRequest;
use Kafka\Protocol\Request\HeartbeatRequest;
use Kafka\Protocol\Request\JoinGroup\ProtocolMetadataJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolNameJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolsJoinGroup;
use Kafka\Protocol\Request\JoinGroup\TopicJoinGroup;
use Kafka\Protocol\Request\JoinGroupRequest;
use Kafka\Protocol\Response\FindCoordinatorResponse;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;
use Kafka\Socket\Socket;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CoreSubscriber
 *
 * @package Kafka\Subscriber
 */
class CoreSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CoreLogicBeforeEvent::NAME => 'onCoreLogicBefore',
            CoreLogicEvent::NAME       => 'onCoreLogic',
            CoreLogicAfterEvent::NAME  => 'onCoreLogicAfter',
            HeartbeatEvent::NAME       => 'onHeartBeat',
        ];
    }

    public function onHeartBeat(): void
    {
//        $commonConfig = new CommonConfig();
//        $heartbeatRequest = new HeartbeatRequest();
//        $heartbeatRequest->setGroupId(String16::value($commonConfig->getGroupId()))
//            ->setMemberId()
//            ->setGenerationId();
    }

    public function onCoreLogicBefore()
    {
        // Join Group
        /** @var CommonConfig $commonConfig */
        $commonConfig = CommonConfig::getInstance();
        $findCoordinatorRequest = new FindCoordinatorRequest();
        $data = $findCoordinatorRequest->setKey(String16::value($commonConfig->getGroupId()))->pack();

        ['host' => $host, 'port' => $port] = Kafka::getInstance()->getRandBroker();
        $socket = new Socket();
        $socket->connect($host, $port)->send($data);
        $socket->revcByKafka($findCoordinatorRequest);

        /** @var FindCoordinatorResponse $response */
        $response = $findCoordinatorRequest->response;
        if ($response->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
            throw new ClientException(sprintf('FindCoordinatorRequest request error, the error message is: %s',
                ProtocolErrorEnum::getTextByCode($response->getErrorCode()->getValue())));
        }

        ClientKafka::getInstance()->setOffsetConnectWithNodeId($response->getPort()->getValue())
                   ->setOffsetConnectWithHost($response->getHost()->getValue())
                   ->setOffsetConnectWithPort($response->getPort()->getValue());

        $joinGroupRequest = new JoinGroupRequest();
        $joinGroupRequest->setGroupId(String16::value($commonConfig->getGroupId()))
                         ->setMemberId(String16::value(''))
                         ->setSessionTimeoutMs(Int32::value($commonConfig->getGroupKeepSessionMaxMs()))
                         ->setProtocols([
                             (new ProtocolsJoinGroup())->setName(
                                 (new ProtocolNameJoinGroup())->setAssignmentStrategy(String16::value(ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR))
                             )->setMetadata(
                                 (new ProtocolMetadataJoinGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                                  ->setSubscription([
                                                                      (new TopicJoinGroup())->setTopic(String16::value('caiwenhui'))
                                                                  ])
                                                                  ->setUserData(Bytes32::value(''))
                             )
                         ]);

    }

    public function onCoreLogic()
    {

    }


    public function onCoreLogicAfter()
    {
        // nothing to do
    }
}