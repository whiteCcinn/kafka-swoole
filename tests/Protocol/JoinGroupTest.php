<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

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
//
//    /**
//     * @author caiwenhui
//     * @group  encode
//     * @return string
//     * @throws \Kafka\Exception\ProtocolTypeException
//     * @throws \ReflectionException
//     */
//    public function testEncode()
//    {
//        /** @var JoinGroupRequest $protocol */
//        $protocol = $this->protocol;
//        $protocol->setGroupId(String16::value('kafka-swoole'))
//                 ->setSessionTimeoutMs(Int32::value(30000))
//                 ->setMemberId(String16::value(''))
//                 ->setProtocolType(String16::value('consumer'))
//                 ->setProtocols([
//                     (new ProtocolsJoinGroup())->setName(
//                         (new ProtocolNameJoinGroup())->setAssignmentStrategy(String16::value(ProtocolPartitionAssignmentStrategyEnum::RANGE_ASSIGNOR))
//                     )->setMetadata(
//                         (new ProtocolMetadataJoinGroup())->setVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
//                                                          ->setSubscription([
//                                                              (new TopicJoinGroup())->setTopic(String16::value('caiwenhui'))
//                                                          ])
//                                                          ->setUserData(Bytes32::value(''))
//                     )
//                 ]);
//        $data = $protocol->pack();
//
//        $expected = '00000054000b00000000000b000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c650000753000000008636f6e73756d65720000000100013000000015000000000001000963616977656e68756900000000';
//        $this->assertSame($expected, bin2hex($data));
//
//        return $data;
//    }

    /**
     * @author  caiwenhui
     * @group   decode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testDecode()
    {
        /** @var JoinGroupRequest $protocol */
        $protocol = $this->protocol;
//        $buffer = '000000c30000000b00000000000100013000316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d35396432383034656436623900316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d3539643238303465643662390000000100316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d35396432383034656436623900000015000000000001000963616977656e68756900000000';
        $buffer = '000001190000000b000000000003000552616e676500316b61666b612d73776f6f6c652d66626563303236352d663366632d346266642d383766642d38313235653931323463656400316b61666b612d73776f6f6c652d66626563303236352d663366632d346266642d383766642d3831323565393132346365640000000200316b61666b612d73776f6f6c652d66626563303236352d663366632d346266642d383766642d38313235653931323463656400000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d63636638383331652d306137632d346463642d623863342d31356337323236666432393000000018000000000001000c6b61666b612d73776f6f6c65000000000000000b000000000003000552616e676500316b61666b612d73776f6f6c652d66626563303236352d663366632d346266642d383766642d38313235653931323463656400316b61666b612d73776f6f6c652d66626563303236352d663366632d346266642d383766642d3831323565393132346365640000000200316b61666b612d73776f6f6c652d66626563303236352d663366632d346266642d383766642d38313235653931323463656400000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d63636638383331652d306137632d346463642d623863342d31356337323236666432393000000018000000000001000c6b61666b612d73776f6f6c6500000000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        var_dump($response->toArray());exit;
        $expected = [
            'errorCode'      => 0,
            'generationId'   => 1,
            'protocolName'   => '0',
            'leader'         => 'kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9',
            'memberId'       => 'kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9',
            'members'        =>
                [
                    0 =>
                        [
                            'memberId' => 'kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9',
                            'metadata' =>
                                [
                                    0 =>
                                        [
                                            'version'      => 0,
                                            'subscription' =>
                                                [
                                                    0 =>
                                                        [
                                                            'topic' => 'caiwenhui',
                                                        ],
                                                ],
                                            'userData'     => '',
                                        ],
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 11,
                ],
            'size'           => 195,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
