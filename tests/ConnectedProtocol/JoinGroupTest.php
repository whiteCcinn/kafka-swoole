<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolPartitionAssignmentStrategyEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\JoinGroup\ProtocolMetadataJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolNameJoinGroup;
use Kafka\Protocol\Request\JoinGroup\ProtocolsJoinGroup;
use Kafka\Protocol\Request\JoinGroup\TopicJoinGroup;
use Kafka\Protocol\Request\JoinGroupRequest;
use Kafka\Protocol\Response\JoinGroupResponse;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class JoinGroupTest extends AbstractProtocolTest
{
    /**
     * @var JoinGroupRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new JoinGroupRequest();
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @return string
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var JoinGroupRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setSessionTimeoutMs(Int32::value(30000))
                 ->setMemberId(String16::value(''))
                 ->setProtocolType(String16::value('consumer'))
                 ->setProtocols([
                     (new ProtocolsJoinGroup())->setName(
//                    (new ProtocolNameJoinGroup())->setAssignmentStrategy(String16::value(ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR))
                         (new ProtocolNameJoinGroup())->setAssignmentStrategy(String16::value('range'))
                     )->setMetadata(
                         (new ProtocolMetadataJoinGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                                          ->setSubscription([
                                                              (new TopicJoinGroup())->setTopic(String16::value('caiwenhui'))
                                                          ])
                                                          ->setUserData(Bytes32::value(''))
                     )
                 ]);
        $data = $protocol->pack();

        $expected = '00000058000b00000000000b000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c650000753000000008636f6e73756d657200000001000572616e676500000015000000000001000963616977656e68756900000000';
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
        /** @var JoinGroupRequest $protocol */
        $protocol = $this->protocol;

        SocketServer::getInstance()->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });


        /** @var JoinGroupResponse $response */
        $response = $protocol->response;
        $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $response->getErrorCode()->getValue());
    }
}
