<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use App\App;
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
            OffsetCommitEvent::NAME    => 'onOffsetCommit'
        ];
    }

    /**
     * @param HeartbeatEvent $event
     */
    public function onHeartBeat(HeartbeatEvent $event): void
    {
        go(function () {
            defer(function () {
                throw new ClientException('Heartbeat request coroutine aborted unexpectedly');
            });
            $heartbeatIntervalMs = App::$commonConfig->getHeartbeatIntervalMs();
            $sleepTime = $heartbeatIntervalMs / 1000;
            $socket = new Socket();
            $heartbeatRequest = new HeartbeatRequest();
            while (true) {
                try {
                    $heartbeatRequest->setMemberId(String16::value(ClientKafka::getInstance()->getMemberId()))
                                     ->setGroupId(String16::value(App::$commonConfig->getGroupId()))
                                     ->setGenerationId(Int32::value(ClientKafka::getInstance()->getGenerationId()));

                    $data = $heartbeatRequest->pack();
                    $socket->connect(ClientKafka::getInstance()->getOffsetConnectHost(),
                        ClientKafka::getInstance()->getOffsetConnectPort())->send($data);
                    $socket->revcByKafka($heartbeatRequest);

                    /** @var HeartbeatResponse $response */
                    $response = $heartbeatRequest->response;

                    // rebalance need RejoinGroup
                    if ($response->getErrorCode()->getValue() === ProtocolErrorEnum::REBALANCE_IN_PROGRESS) {
                        if (!ClientKafka::getInstance()->isRebalancing()) {
                            echo ProtocolErrorEnum::getTextByCode(ProtocolErrorEnum::REBALANCE_IN_PROGRESS) . PHP_EOL;
                            $this->rebalance(App::$commonConfig, $socket);
                        }
                    } elseif ($response->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                        throw new HeartbeatRequestException(sprintf('HeartbeatRequest request error, the error message is: %s',
                            ProtocolErrorEnum::getTextByCode($response->getErrorCode()->getValue())));
                    }
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                    $socket->close();
                } catch (\Error $error) {
                    var_dump($error->getMessage());
                    $socket->close();
                }
                echo sprintf('%s:Heartbeat request every %s seconds...' . PHP_EOL,
                    ClientKafka::getInstance()->getMemberId(), $sleepTime);
                \co::sleep($sleepTime);
            }
        });
    }

    /**
     * @param OffsetCommitEvent $event
     */
    public function onOffsetCommit(OffsetCommitEvent $event): void
    {
        // HighLevel Auto Offset Commit
        if (env('KAFKA_CLIENT_API_MODE') === ClientApiModeEnum::getTextByCode(ClientApiModeEnum::HIGH_LEVEL)) {
            go(function () {
//                defer(function () {
//                    throw new ClientException('OffsetCommit request coroutine aborted unexpectedly');
//                });
                $offsetCommitRequest = new OffsetCommitRequest();
                $autoCommitInterval = App::$commonConfig->getAutoCommitIntervalMs() / 1000;
                while (true) {
                    \co::sleep($autoCommitInterval);
                    foreach (ClientKafka::getInstance()->getSelfLeaderTopicPartition() as $leaderId => $topicPartitions)
                    {
                        $setTopics = [];
                        foreach ($topicPartitions as $topic => $partitions) {
                            $setPartitions = [];
                            foreach ($partitions as $partition) {
                                $setPartitions[] = (new PartitionsOffsetCommit())->setPartitionIndex(Int32::value($partition))
                                                                                 ->setCommittedOffset(Int64::value(ClientKafka::getInstance()
                                                                                                                              ->getTopicPartitionOffsetByTopicPartition(
                                                                                                                                  $topic,
                                                                                                                                  $partition
                                                                                                                              )
                                                                                 ))
                                                                                 ->setCommittedMetadata(String16::value(''));
                            }
                            $setTopics[] = (new TopicsOffsetCommit())->setPartitions($setPartitions)
                                                                     ->setName(String16::value($topic));
                        }
                        $offsetCommitRequest->setTopics($setTopics)
                                            ->setGroupId(String16::value(App::$commonConfig->getGroupId()));
                        $socket = Kafka::getInstance()->getSocketByNodeId($leaderId);
                        $data = $offsetCommitRequest->pack();
                        $socket->send($data);
                        $socket->revcByKafka($offsetCommitRequest);

                        /** @var OffsetCommitResponse $response */
                        $response = $offsetCommitRequest->response;
                        foreach ($response->getTopics() as $topic) {
                            foreach ($topic->getPartitions() as $partition) {
                                if ($partition->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                                    throw new OffsetCommitRequestException(sprintf('OffsetCommitRequest request error, the error message is: %s',
                                        ProtocolErrorEnum::getTextByCode($partition->getErrorCode()->getValue())));
                                }
                            }
                        }
                    }
                    echo sprintf('%s:Auto offsetCommit request every %s seconds...' . PHP_EOL,
                        ClientKafka::getInstance()->getMemberId(), $autoCommitInterval);
                }
            });
        }
    }

    /**
     * @throws ClientException
     * @throws FindCoordinatorRequestException
     * @throws HeartbeatRequestException
     * @throws JoinGroupRequestException
     * @throws ListOffsetsRequestException
     * @throws OffsetFetchRequestException
     * @throws SyncGroupRequestException
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    public function onCoreLogicBefore()
    {
        /** @var CommonConfig $commonConfig */
        $commonConfig = App::$commonConfig;

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
        $this->rebalance($commonConfig, $socket);
        // ListsOffsets...
        $topicsPartitionLeader = Kafka::getInstance()->getTopicsPartitionLeader();
        $point = [];
        foreach (ClientKafka::getInstance()->getSelfTopicPartition() as $topic => $partitions) {
            $topicsListsOffsets = (new TopicsListsOffsets())->setTopic(String16::value($topic));
            foreach ($partitions as $partition) {
                $partitionsListsOffsets = (new PartitionsListsOffsets())->setPartition(Int32::value($partition))
                                                                        ->setTimestamp(Int64::value(-1));
                $point[$topicsPartitionLeader[$topic][$partition]][$topic][$partition] = [
                    'topicsListsOffsets'     => $topicsListsOffsets,
                    'partitionsListsOffsets' => $partitionsListsOffsets
                ];
            }
        }

        $topics = [];
        foreach ($point as $leader => $topicPartition) {
            $listOffsetsRequest = new ListOffsetsRequest();
            $socket = new Socket();
            foreach ($topicPartition as $topic => $compositePartitions) {
                $partitionsListsOffsetsArray = [];
                foreach ($compositePartitions as $partition => $structure) {
                    ClientKafka::getInstance()->setTopicPartitionSocket($topic, $partition, $socket);
                    ['topicsListsOffsets' => $topicsListsOffsets, 'partitionsListsOffsets' => $partitionsListsOffsets] = $structure;
                    $partitionsListsOffsetsArray[] = $partitionsListsOffsets;
                }
                $topicsListsOffsets->setPartitions($partitionsListsOffsetsArray);
                $topics[] = $topicsListsOffsets;
            }
            $listOffsetsRequest->setTopics($topics);
            $data = $listOffsetsRequest->pack();
            ['host' => $host, 'port' => $port] = Kafka::getInstance()->getBrokerInfoByNodeId($leader);
            $socket->connect($host, $port)->send($data);
            $socket->revcByKafka($listOffsetsRequest);
            /** @var ListOffsetsResponse $response */
            $response = $listOffsetsRequest->response;
            foreach ($response->getResponses() as $response) {
                foreach ($response->getPartitionResponses() as $partitionResponse) {
                    if ($partitionResponse->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                        throw new ListOffsetsRequestException(sprintf('ListOffsetRequest request error, the error message is: %s',
                            ProtocolErrorEnum::getTextByCode($partitionResponse->getErrorCode()->getValue())));
                    }

                    if (count($partitionResponse->getOffsets()) > 1) {
                        [$offset, $highWatermark] = $partitionResponse->getOffsets();
                        $offset = $offset->getValue();
                        $highWatermark = $highWatermark->getValue();
                    } else {
                        $offset = current($partitionResponse->getOffsets())->getValue();
                        $highWatermark = $offset;
                    }
                    ClientKafka::getInstance()->setTopicPartitionListOffsets(
                        $response->getTopic()->getValue(),
                        $partitionResponse->getPartition()->getValue(),
                        $offset,
                        $highWatermark
                    );
                }
            }

            // OffsetFetch...
            $offsetFetchRequest = new OffsetFetchRequest();
            $offsetFetchRequest->setGroupId(String16::value($commonConfig->getGroupId()));
            $setTopics = [];
            foreach (ClientKafka::getInstance()->getSelfTopicPartition() as $topic => $partitions) {
                $topicsOffsetFetch = (new TopicsOffsetFetch())->setTopic(String16::value($topic));
                $setPartitions = [];
                foreach ($partitions as $partition) {
                    $setPartitions[] = (new PartitionsOffsetFetch())->setPartition(Int32::value($partition));
                }
                $setTopics[] = $topicsOffsetFetch->setPartitions($setPartitions);
            }
            $offsetFetchRequest->setTopics($setTopics);
            $data = $offsetFetchRequest->pack();
            $socket = ClientKafka::getInstance()->getOffsetConnectSocket();
            $socket->send($data);
            $socket->revcByKafka($offsetFetchRequest);

            /** @var OffsetFetchResponse $response */
            $response = $offsetFetchRequest->response;
            try {
                $needChangeApiVersion = [];
                foreach ($response->getResponses() as $response) {
                    foreach ($response->getPartitionResponses() as $partitionResponse) {
                        // need change api version
                        if ($partitionResponse->getOffset()->getValue() === -1 && $partitionResponse->getMetadata()
                                                                                                    ->getValue() === '') {
                            $needChangeApiVersion[] = true;
                        } else {
                            if ($partitionResponse->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                                throw new OffsetFetchRequestException(sprintf('Api Version 0, OffsetFetchRequest request error, the error message is: %s',
                                    ProtocolErrorEnum::getTextByCode($partitionResponse->getErrorCode()->getValue())));
                            }
                        }
                        $needChangeApiVersion[] = false;

                        ClientKafka::getInstance()->setTopicPartitionOffset(
                            $response->getTopic()->getValue(),
                            $partitionResponse->getPartition()->getValue(),
                            $partitionResponse->getOffset()->getValue()
                        );

                    }
                }
                if (count(array_unique($needChangeApiVersion)) === 1 && current($needChangeApiVersion) === true) {
                    throw new ClientException(
                        sprintf('Offset does not exist in zookeeper, but in kafka. Therefore, API version needs to be changed')
                    );
                }
            } catch (ClientException $exception) {
                $offsetFetchRequest->getRequestHeader()
                                   ->setApiVersion(Int16::value(ProtocolVersionEnum::API_VERSION_1));
                $data = $offsetFetchRequest->pack();
                $socket->send($data);
                $socket->revcByKafka($offsetFetchRequest);
                /** @var OffsetFetchResponse $response */
                $response = $offsetFetchRequest->response;
                foreach ($response->getResponses() as $response) {
                    foreach ($response->getPartitionResponses() as $partitionResponse) {
                        if ($partitionResponse->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                            throw new OffsetFetchRequestException(sprintf('Api Version 1, OffsetFetchRequest request error, the error message is: %s',
                                ProtocolErrorEnum::getTextByCode($partitionResponse->getErrorCode()->getValue())));
                        }

                        ClientKafka::getInstance()->setTopicPartitionOffset(
                            $response->getTopic()->getValue(),
                            $partitionResponse->getPartition()->getValue(),
                            $partitionResponse->getOffset()->getValue()
                        );
                    }
                }
            }
        }
    }

    /**
     * Client operation：
     * 1. Fetch message
     * 2. Commit offset
     */
    public function onCoreLogic()
    {
        // Heartbeat...
        dispatch(new HeartbeatEvent(), HeartbeatEvent::NAME);

        go(function () {
//            defer(function () {
//                throw new ClientException('Fetch request coroutine aborted unexpectedly');
//            });
            while (true) {
                // fetch data
                $fetchRequest = new FetchRequest();

                foreach (ClientKafka::getInstance()->getSelfLeaderTopicPartition() as $leaderId => $topicPartitions) {
                    $setTopics = [];
                    foreach ($topicPartitions as $topic => $partitions) {
                        $topicsFetch = (new TopicsFetch())->setTopic(String16::value($topic));
                        $setPartitions = [];
                        foreach ($partitions as $partition) {
                            $partitionsFetch = (new PartitionsFetch())->setPartition(Int32::value($partition))
                                                                      ->setFetchOffset(Int64::value(
                                                                          ClientKafka::getInstance()
                                                                                     ->getTopicPartitionOffsetByTopicPartition(
                                                                                         $topic,
                                                                                         $partition
                                                                                     ) + 1
                                                                      ))
                                                                      ->setPartitionMaxBytes(Int32::value(65536));
                            $setPartitions[] = $partitionsFetch;
                        }
                        $setTopics[] = $topicsFetch->setPartitions($setPartitions);
                    }

                    // todo : config setting
                    $fetchRequest->setTopics($setTopics)->setMinBytes(Int32::value(1000))
                                 ->setMaxWaitTime(Int32::value(1000));
                    $data = $fetchRequest->pack();
                    $socket = Kafka::getInstance()->getSocketByNodeId($leaderId);
                    $socket->send($data);
                    $socket->revcByKafka($fetchRequest);

                    /** @var FetchResponse $response */
                    $response = $fetchRequest->response;
                    $messages = [];
                    foreach ($response->getResponses() as $response) {
                        foreach ($response->getPartitionResponses() as $partitionResponse) {
                            if ($partitionResponse->getPartitionHeader()
                                                  ->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                                throw new FetchRequestException(sprintf('FetchRequest request error, the error message is: %s',
                                    ProtocolErrorEnum::getTextByCode($partitionResponse->getPartitionHeader()
                                                                                       ->getErrorCode()->getValue())));
                            }

                            foreach ($partitionResponse->getRecordSet() as $recordSet) {
                                $messages[] = [
                                    'topic'     => $response->getTopic()->getValue(),
                                    'partition' => $partitionResponse->getPartitionHeader()->getPartition()->getValue(),
                                    'offset'    => $recordSet->getOffset()->getValue(),
                                    'message'   => $recordSet->getMessage()->getValue()->getValue()
                                ];
                            }
                        }
                    }

                    foreach ($messages as $item) {
                        ['topic' => $topic, 'partition' => $partition, 'offset' => $offset, 'message' => $message] = $item;
                        dispatch(new FetchMessageEvent($topic, $partition, $offset, $message), FetchMessageEvent::NAME);
                    }
                }
            }
        });


        // OffsetCommit...
        dispatch(new OffsetCommitEvent(), OffsetCommitEvent::NAME);
    }


    public function onCoreLogicAfter()
    {
        // nothing to do
    }

    /**
     * @param $commonConfig
     * @param $socket
     *
     * @return array
     * @throws ClientException
     * @throws HeartbeatRequestException
     * @throws JoinGroupRequestException
     * @throws SyncGroupRequestException
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    private function rebalance($commonConfig, $socket): array
    {
        Rebalance:
        ClientKafka::getInstance()->setIsRebalancing(true);
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
                foreach ($member->getMetadata()->getSubscription() as $subscription) {
                    $topicMemberIds[$subscription->getTopic()->getValue()][] = $member->getMemberId()->getValue();
                    $memberIdTopics[$member->getMemberId()->getValue()][] = $subscription->getTopic()->getValue();
                }
            }
            ClientKafka::getInstance()->setTopicMemberIds($topicMemberIds)->setMemberIdTopics($memberIdTopics);

            // leader execute partitionAssignStrategy...
            $topicPartitions = Kafka::getInstance()->getPartitions();
            switch (ClientKafka::getInstance()->getProtocolName()) {
                case ProtocolPartitionAssignmentStrategyEnum::getTextByCode(
                    ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR
                ):
                    $topics = array_keys($topicMemberIds);
                    foreach ($topics as $topic) {
                        $partitionNum = count($topicPartitions[$topic]);
                        $topicConsumerNum = count($topicMemberIds[$topic]);
                        $partitionAssignNum = ceil($partitionNum / $topicConsumerNum);
                        $partitionIndex = 0;
                        foreach ($topicMemberIds[$topic] as $memberId) {
                            $i = 0;
                            while ($i < $partitionAssignNum) {
                                $fetchSpec[$memberId][$topic][] = $partitionIndex;
                                $partitionIndex++;
                                $i++;
                            }
                        }
                    }
                    break;
                case ProtocolPartitionAssignmentStrategyEnum::getTextByCode(
                    ProtocolPartitionAssignmentStrategyEnum::ROUND_ROBIN_ASSIGNOR
                ):
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
                case ProtocolPartitionAssignmentStrategyEnum::getTextByCode(
                    ProtocolPartitionAssignmentStrategyEnum::STICKY_ASSIGNOR
                ):
                    // todo: kafka 0.11 support
                    break;
                default:
                    throw new ClientException(sprintf('PartitionAssignmentStrategy error, the strategy is : %s',
                        ClientKafka::getInstance()->getProtocolName()));
            }
        } else {
            ClientKafka::getInstance()->setIsLeader(false);
        }

        // SyncGroup...
        $syncGroupRequest = new SyncGroupRequest();
        if (ClientKafka::getInstance()->isLeader()) {
            $assignments = [];
            foreach ($fetchSpec as $memberId => $tpt) {
                $topic = key($tpt);
                $partitions = current($tpt);
                $groupAssignment = (new GroupAssignmentsSyncGroup())->setMemberId(
                    String16::value($memberId)
                );
                $partitionAssignments = [];
                $pushPartition = [];
                $partitionAssignmentsSyncGroup = new PartitionAssignmentsSyncGroup();
                foreach ($partitions as $partitionIndex) {
                    $pushPartition[] = Int32::value($partitionIndex);
                }
                $partitionAssignmentsSyncGroup->setTopic(String16::value($topic))->setPartition($pushPartition);
                $partitionAssignments[] = $partitionAssignmentsSyncGroup;
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
        // The group is rebalancing, so a rejoin is needed.
        if ($response->getErrorCode()->getValue() === ProtocolErrorEnum::REBALANCE_IN_PROGRESS) {
            echo ProtocolErrorEnum::getTextByCode(ProtocolErrorEnum::REBALANCE_IN_PROGRESS) . PHP_EOL;
//            $this->rebalance($commonConfig, $socket);
            goto Rebalance;
        } elseif ($response->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
            throw new SyncGroupRequestException(sprintf('SyncGroupRequest request error, the error message is: %s',
                ProtocolErrorEnum::getTextByCode($response->getErrorCode()->getValue())));
        }
        $selfTopicPartition = [];
        $selfLeaderTopicPartition = [];
        foreach ($response->getAssignment()->getPartitionAssignment() as $partitionAssignment) {
            foreach ($partitionAssignment->getPartition() as $partition) {
                $topicValue = $partitionAssignment->getTopic()->getValue();
                $partitionValue = $partition->getValue();
                $selfTopicPartition[$topicValue][] = $partitionValue;
                $leaderId = Kafka::getInstance()->getLeaderByTopicPartition(
                    $topicValue,
                    $partitionValue
                );
                $selfLeaderTopicPartition[$leaderId][$topicValue][] = $partitionValue;
            }
        }
        ClientKafka::getInstance()->setSelfTopicPartition($selfTopicPartition);
        ClientKafka::getInstance()->setSelfLeaderTopicPartition($selfLeaderTopicPartition);

        ClientKafka::getInstance()->setIsRebalancing(false);

//        return [$data, $response, $topics, $topic, $partitions, $socket, $partition];
        return [];
    }
}