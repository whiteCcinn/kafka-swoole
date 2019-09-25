<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolPartitionAssignmentStrategyEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\FindCoordinatorRequest;
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
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class SyncGroupTest extends AbstractProtocolTest
{
    /**
     * @var SyncGroupRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new SyncGroupRequest();
    }

    /**
     * @author caiwenhui
     * @group  connectedEncode
     * @return string
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var SyncGroupRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setGenerationId(Int32::value(1))
                 ->setMemberId(String16::value('kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9'))
                 ->setAssignments([
                     (new GroupAssignmentsSyncGroup())->setMemberId(String16::value('kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9'))
                                                      ->setMemberAssignment(
                                                          (new MemberAssignmentsSyncGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                                                            ->setUserData(Bytes32::value(''))
                                                                                            ->setPartitionAssignment([
                                                                                                (new PartitionAssignmentsSyncGroup())->setPartition([
                                                                                                    Int32::value(0)
                                                                                                ])
                                                                                                                                     ->setTopic(String16::value('caiwenhui'))
                                                                                            ])
                                                      )
                 ]);
        $data = $protocol->pack();

        $expected = '000000b3000e00000000000e000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c650000000100316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d3539643238303465643662390000000100316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d3539643238303465643662390000001d000000000001000963616977656e687569000000010000000000000000';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

    /**
     * @author  caiwenhui
     *
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testSend()
    {
        /** @var FindCoordinatorRequest $protocol */
        $protocol = new FindCoordinatorRequest();
        $protocol->setKey(String16::value('kafka-swoole'));
        $data = $protocol->pack();

        SocketServer::getInstance(true)->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var FindCoordinatorResponse $coordinatorResponse */
        $coordinatorResponse = $protocol->response;

        /** @var JoinGroupRequest $protocol */
        $protocol = new JoinGroupRequest();
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setSessionTimeoutMs(Int32::value(30000))
                 ->setMemberId(String16::value(''))
                 ->setProtocolType(String16::value('consumer'))
                 ->setProtocols([
                     (new ProtocolsJoinGroup())->setName(
                         (new ProtocolNameJoinGroup())->setAssignmentStrategy(String16::value(ProtocolPartitionAssignmentStrategyEnum::getTextByCode(ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR)))
                     )->setMetadata(
                         (new ProtocolMetadataJoinGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                          ->setSubscription([
                                                              (new TopicJoinGroup())->setTopic(String16::value('caiwenhui'))
                                                          ])
                                                          ->setUserData(Bytes32::value(''))
                     )
                 ]);
        $data = $protocol->pack();

        SocketServer::getInstance(true, false)->run($coordinatorResponse->getHost()->getValue(),
            $coordinatorResponse->getPort()->getValue(), function () use ($data) {
                return $data;
            }, function (string $data, Client $client) use ($protocol) {
                $protocol->response->unpack($data, $client);
            });

        /** @var JoinGroupResponse $joinGroupResponse */
        $joinGroupResponse = $protocol->response;

        /** @var SyncGroupRequest $protocol */
        $protocol = new SyncGroupRequest();
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setGenerationId(Int32::value($joinGroupResponse->getGenerationId()->getValue()))
                 ->setMemberId(String16::value($joinGroupResponse->getMemberId()->getValue()))
                 ->setAssignments([
                     (new GroupAssignmentsSyncGroup())->setMemberId(String16::value($joinGroupResponse->getMemberId()
                                                                                                      ->getValue()))
                                                      ->setMemberAssignment(
                                                          (new MemberAssignmentsSyncGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                                                            ->setUserData(Bytes32::value(''))
                                                                                            ->setPartitionAssignment([
                                                                                                (new PartitionAssignmentsSyncGroup())->setPartition([
                                                                                                    Int32::value(0)
                                                                                                ])
                                                                                                                                     ->setTopic(String16::value('caiwenhui'))
                                                                                            ])
                                                      )
                 ]);
        $data = $protocol->pack();
        SocketServer::getInstance(false)->run($coordinatorResponse->getHost()->getValue(),
            $coordinatorResponse->getPort()->getValue(), function () use ($data) {
                return $data;
            }, function (string $data, Client $client) use ($protocol) {
                var_dump(bin2hex($data));
                $protocol->response->unpack($data, $client);
            });

        /** @var SyncGroupResponse $syncGroupResponse */
        $syncGroupResponse = $protocol->response;
        $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $syncGroupResponse->getErrorCode()->getValue());
    }
}
