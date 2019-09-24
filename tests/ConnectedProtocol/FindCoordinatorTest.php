<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\FindCoordinatorRequest;
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
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class FindCoordinatorTest extends AbstractProtocolTest
{
    /**
     * @var FindCoordinatorRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new FindCoordinatorRequest();
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
        /** @var FindCoordinatorRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setKey(String16::value('kafka-swoole'));
        $data = $protocol->pack();

        $expected = '00000024000a00000000000a000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c65';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

    /**
     * @param string $data
     *
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testSend(string $data)
    {
        /** @var FindCoordinatorRequest $protocol */
        $protocol = $this->protocol;

        SocketServer::getInstance()->run('mkafka4', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var FindCoordinatorResponse $response */
        $response = $protocol->response;
        var_dump($protocol->response->toArray());
        $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $response->getErrorCode()->getValue());


        /** @var JoinGroupRequest $protocol */
        $protocol = new JoinGroupRequest();
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setSessionTimeoutMs(Int32::value(30000))
                 ->setMemberId(String16::value(''))
                 ->setProtocolType(String16::value('consumer'))
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
        $data = $protocol->pack();

        SocketServer::getInstance()->run('mkafka2', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        var_dump($protocol->response->toArray());


    }
}
