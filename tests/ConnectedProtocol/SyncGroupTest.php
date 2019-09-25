<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\SyncGroup\AssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\GroupAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\MemberAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\PartitionAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroupRequest;
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
                (new AssignmentsSyncGroup())->setMemberId(String16::value('kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9'))
                ->setAssignment([
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
                ])
            ]);
        $data = $protocol->pack();

        $expected = '000000250003000000000003000c6b61666b612d73776f6f6c6500000001000963616977656e687569';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

    /**
     * @param string $data
     *
     * @author  caiwenhui
     * @depends testEncode
     */
    public function testSend(string $data)
    {
        /** @var SyncGroupRequest $protocol */
        $protocol = $this->protocol;

        SocketServer::getInstance()->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var SyncGroupResponse $response */
        $response = $protocol->response;
    }
}
