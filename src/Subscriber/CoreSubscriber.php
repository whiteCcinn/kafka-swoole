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
use Kafka\Exception\RequestException\FindCoordinatorRequestException;
use Kafka\Exception\RequestException\JoinGroupRequestException;
use Kafka\Exception\RequestException\SyncGroupRequestException;
use Kafka\Kafka;
use Kafka\Manager\MetadataManager;
use Kafka\Protocol\Request\FindCoordinatorRequest;
use Kafka\Protocol\Request\HeartbeatRequest;
use Kafka\Protocol\Request\JoinGroup\ProtocolMetadataJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolNameJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolsJoinGroup;
use Kafka\Protocol\Request\JoinGroup\TopicJoinGroup;
use Kafka\Protocol\Request\JoinGroupRequest;
use Kafka\Protocol\Request\SyncGroup\GroupAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\MemberAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\PartitionAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroupRequest;
use Kafka\Protocol\Response\FindCoordinatorResponse;
use Kafka\Protocol\Response\JoinGroupResponse;
use Kafka\Protocol\Response\SyncGroupResponse;
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

    /**
     * Client front-end operation：
     * 1、FindCoordinator
     * 2、JoinGroup
     * 3、SyncGroup
     *
     * @throws ClientException
     * @throws FindCoordinatorRequestException
     * @throws JoinGroupRequestException
     * @throws SyncGroupRequestException
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    public function onCoreLogicBefore()
    {
        /** @var CommonConfig $commonConfig */
        $commonConfig = CommonConfig::getInstance();

        // FindCoordinator...
        $findCoordinatorRequest = new FindCoordinatorRequest();
        $data = $findCoordinatorRequest->setKey(String16::value($commonConfig->getGroupId()))->pack();
        ['host' => $host, 'port' => $port] = Kafka::getInstance()->getRandBroker();
        $socket = new Socket();
        $socket->connect($host, $port)->send($data);
        $socket->revcByKafka($findCoordinatorRequest);
        $socket->close();

        /** @var FindCoordinatorResponse $response */
        $response = $findCoordinatorRequest->response;
        if ($response->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
            throw new FindCoordinatorRequestException(sprintf('FindCoordinatorRequest request error, the error message is: %s',
                ProtocolErrorEnum::getTextByCode($response->getErrorCode()->getValue())));
        }

        ClientKafka::getInstance()->setOffsetConnectWithNodeId($response->getPort()->getValue())
                   ->setOffsetConnectWithHost($response->getHost()->getValue())
                   ->setOffsetConnectWithPort($response->getPort()->getValue());

        // JoinGroup...
        $joinGroupRequest = new JoinGroupRequest();
        $subscriptions = [];
        foreach (explode(',', $commonConfig->getTopicNames()) as $topicName) {
            $subscriptions[] = (new TopicJoinGroup())->setTopic(String16::value($topicName));
        }
        $joinGroupRequest->setGroupId(String16::value($commonConfig->getGroupId()))
                         ->setMemberId(String16::value(''))
                         ->setSessionTimeoutMs(Int32::value($commonConfig->getGroupKeepSessionMaxMs()))
                         ->setProtocols([
                             (new ProtocolsJoinGroup())->setName(
                                 (new ProtocolNameJoinGroup())->setAssignmentStrategy(
                                     String16::value(
                                         ProtocolPartitionAssignmentStrategyEnum::getTextByCode(
                                             ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR
                                         )
                                     )
                                 )
                             )->setMetadata(
                                 (new ProtocolMetadataJoinGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                                  ->setSubscription($subscriptions)
                                                                  ->setUserData(Bytes32::value(''))
                             )
                         ]);
        $data = $joinGroupRequest->pack();
        $socket->connect(
            ClientKafka::getInstance()->getOffsetConnectHost(),
            ClientKafka::getInstance()->getOffsetConnectPort()
        )->send($data);
        $socket->revcByKafka($joinGroupRequest);
        ClientKafka::getInstance()->setOffsetConnectWithSocket($socket);

        /** @var JoinGroupResponse $response */
        $response = $joinGroupRequest->response;
        if ($response->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
            throw new JoinGroupRequestException(sprintf('JoinGroupRequest request error, the error message is: %s',
                ProtocolErrorEnum::getTextByCode($response->getErrorCode()->getValue())));
        }
        ClientKafka::getInstance()->setGenerationId($response->getGenerationId()->getValue())
                   ->setProtocolName($response->getProtocolName()->getValue())
                   ->setLeader($response->getLeader()->getValue())
                   ->setMemberId($response->getMemberId()->getValue());

        $fetchSpec = [];
        // if leaderId === memberId , That Client is leader, which will receive all members Info
        if ($response->getLeader()->getValue() === $response->getMemberId()->getValue()) {
            ClientKafka::getInstance()->setIsLeader(true);
            ClientKafka::getInstance()->setMembers($response->getMembers());
            $topicMemberIds = $memberIdTopics = [];
            foreach (ClientKafka::getInstance()->getMembers() as $member) {
                foreach ($member->getMetadata() as $metadata) {
                    foreach ($metadata->getSubscription() as $subscription) {
                        $topicMemberIds[$subscription->getTopic()->getValue()][] = $member->getMemberId()->getValue();
                        $memberIdTopics[$member->getMemberId()->getValue()][] = $subscription->getTopic()->getValue();
                    }
                }
            }
            array_unique($topicMemberIds);
            array_unique($memberIdTopics);
            ClientKafka::getInstance()->setTopicMemberIds($topicMemberIds)->setMemberIdTopics($memberIdTopics);

            // leader execute partitionAssignStrategy...
            $topicPartitions = Kafka::getInstance()->getPartitions();
            switch (ClientKafka::getInstance()->getProtocolName()) {
                case ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR:
                    $topics = array_keys($topicMemberIds);
                    foreach ($topics as $topic) {
                        $partitionNum = count($topicPartitions[$topic]);
                        $topicConsumerNum = count($topicMemberIds[$topic]);
                        $partitionAssignNum = ceil($partitionNum / $topicConsumerNum);
                        $partitionIndex = 0;
                        foreach ($topicMemberIds[$topic] as $memberId) {
                            $i = 0;
                            while ($partitionNum) {
                                if ($i < $partitionAssignNum) {
                                    $fetchSpec[$memberId][$topic][] = $partitionIndex;
                                    $partitionIndex++;
                                    $i++;
                                }
                                $partitionNum--;
                            }
                        }
                    }
                    break;
                case ProtocolPartitionAssignmentStrategyEnum::ROUND_ROBIN_ASSIGNOR:
                    foreach ($topicPartitions as $topic => $partitions) {
                        foreach ($topicMemberIds as $topic2 => $memberIds) {
                            if ($topic === $topic2) {
                                while (($partitionIndex = current($partitions)) !== false) {
                                    while (($memberId = current($memberIds)) === false) {
                                        reset($memberIds);
                                    }
                                    next($memberIds);
                                    $fetchSpec[$memberId][$topic][] = $partitionIndex;
                                    next($partitions);
                                }
                                unset($memberIds, $partitions);
                            }
                        }
                    }

                    break;
                case ProtocolPartitionAssignmentStrategyEnum::STICKY_ASSIGNOR:
                    // todo: kafka 0.11 support
                    break;
                default:
                    throw new ClientException(sprintf('PartitionAssignmentStrategy error, the strategy is : %s',
                        ClientKafka::getInstance()->getProtocolName()));
            }
        } else {
            // for waiting leader SyncGroup
            ClientKafka::getInstance()->setIsLeader(false);
//            \co::sleep(3);
        }

        // SyncGroup...
        $syncGroupRequest = new SyncGroupRequest();
        if (ClientKafka::getInstance()->isLeader()) {
            $assignments = [];
            foreach ($fetchSpec as $memberId => $tpt) {
                ['topic' => $topic, 'partition' => $partitions] = $tpt;
                $groupAssignment = (new GroupAssignmentsSyncGroup())->setMemberId(
                    String16::value(ClientKafka::getInstance()->getMemberId())
                );
                $partitionAssignments = [];
                foreach ($partitions as $partitionIndex) {
                    $partitionAssignments[] = (new PartitionAssignmentsSyncGroup())->setPartition([
                        Int32::value($partitionIndex)
                    ])->setTopic(String16::value($topic));
                }
                $groupAssignment->setMemberAssignment(
                    (new MemberAssignmentsSyncGroup())->setVersion(
                        Int16::value(ProtocolVersionEnum::API_VERSION_0)
                    )->setUserData(Bytes32::value(''))->setPartitionAssignment($partitionAssignments)
                );
                $assignments[] = $groupAssignment;
            }
        } else {
            $assignments = [
                (new GroupAssignmentsSyncGroup())->setMemberId(String16::value(ClientKafka::getInstance()
                                                                                          ->getMemberId()))
                                                 ->setMemberAssignment(
                                                     (new MemberAssignmentsSyncGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                                                       ->setUserData(Bytes32::value(''))
                                                                                       ->setPartitionAssignment([
                                                                                           (new PartitionAssignmentsSyncGroup())->setPartition([
                                                                                               Int32::value(0)
                                                                                           ])
                                                                                                                                ->setTopic(String16::value(''))
                                                                                       ])
                                                 )
            ];
        }
        $syncGroupRequest->setMemberId(String16::value(ClientKafka::getInstance()->getMemberId()))
                         ->setGenerationId(Int32::value(ClientKafka::getInstance()->getGenerationId()))
                         ->setGroupId(String16::value($commonConfig->getGroupId()))
                         ->setAssignments($assignments);
        $data = $syncGroupRequest->pack();
        $socket = ClientKafka::getInstance()->getOffsetConnectSocket();
        $socket->send($data);
        $socket->revcByKafka($syncGroupRequest);
        /** @var SyncGroupResponse $response */
        $response = $syncGroupRequest->response;
        if ($response->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
            throw new SyncGroupRequestException(sprintf('SyncGroupRequest request error, the error message is: %s',
                ProtocolErrorEnum::getTextByCode($response->getErrorCode()->getValue())));
        }
    }

    public function onCoreLogic()
    {

    }


    public function onCoreLogicAfter()
    {
        // nothing to do
    }
}