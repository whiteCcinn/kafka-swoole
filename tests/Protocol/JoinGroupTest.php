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

        $expected = '00000054000b00000000000b000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c650000753000000008636f6e73756d65720000000100013000000015000000000001000963616977656e68756900000000';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

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
        $buffer = '000000c30000000b00000000000100013000316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d35396432383034656436623900316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d3539643238303465643662390000000100316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d35396432383034656436623900000015000000000001000963616977656e68756900000000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

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
            'responseHeader' =>
                [
                    'correlationId' => 11,
                ],
            'size'           => 195,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

    /**
     * @author  caiwenhui
     * @group   decode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testDecode2()
    {
        /** @var JoinGroupRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000001b70000000b000000000005000552616e676500316b61666b612d73776f6f6c652d66323637396337372d323064612d346365322d393730392d36393166626663623766323600316b61666b612d73776f6f6c652d66323637396337372d323064612d346365322d393730392d3639316662666362376632360000000400316b61666b612d73776f6f6c652d66323637396337372d323064612d346365322d393730392d36393166626663623766323600000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d31323031643564632d313530332d346631352d383837382d32653230343063386461323200000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d39326231373763342d326635342d346233662d393732302d35633231643463393132353300000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d64383332373066312d646433312d343238652d396266382d36306133323538653338313600000018000000000001000c6b61666b612d73776f6f6c65000000000000000b000000000005000552616e676500316b61666b612d73776f6f6c652d66323637396337372d323064612d346365322d393730392d36393166626663623766323600316b61666b612d73776f6f6c652d66323637396337372d323064612d346365322d393730392d3639316662666362376632360000000400316b61666b612d73776f6f6c652d66323637396337372d323064612d346365322d393730392d36393166626663623766323600000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d31323031643564632d313530332d346631352d383837382d32653230343063386461323200000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d39326231373763342d326635342d346233662d393732302d35633231643463393132353300000018000000000001000c6b61666b612d73776f6f6c650000000000316b61666b612d73776f6f6c652d64383332373066312d646433312d343238652d396266382d36306133323538653338313600000018000000000001000c6b61666b612d73776f6f6c6500000000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'errorCode'      => 0,
            'generationId'   => 5,
            'protocolName'   => 'Range',
            'leader'         => 'kafka-swoole-f2679c77-20da-4ce2-9709-691fbfcb7f26',
            'memberId'       => 'kafka-swoole-f2679c77-20da-4ce2-9709-691fbfcb7f26',
            'members'        =>
                [
                    0 =>
                        [
                            'memberId' => 'kafka-swoole-f2679c77-20da-4ce2-9709-691fbfcb7f26',
                            'metadata' =>
                                [
                                    'version'      => 0,
                                    'subscription' =>
                                        [
                                            0 =>
                                                [
                                                    'topic' => 'kafka-swoole',
                                                ],
                                        ],
                                    'userData'     => '',
                                ],
                        ],
                    1 =>
                        [
                            'memberId' => 'kafka-swoole-1201d5dc-1503-4f15-8878-2e2040c8da22',
                            'metadata' =>
                                [
                                    'version'      => 0,
                                    'subscription' =>
                                        [
                                            0 =>
                                                [
                                                    'topic' => 'kafka-swoole',
                                                ],
                                        ],
                                    'userData'     => '',
                                ],
                        ],
                    2 =>
                        [
                            'memberId' => 'kafka-swoole-92b177c4-2f54-4b3f-9720-5c21d4c91253',
                            'metadata' =>
                                [
                                    'version'      => 0,
                                    'subscription' =>
                                        [
                                            0 =>
                                                [
                                                    'topic' => 'kafka-swoole',
                                                ],
                                        ],
                                    'userData'     => '',
                                ],
                        ],
                    3 =>
                        [
                            'memberId' => 'kafka-swoole-d83270f1-dd31-428e-9bf8-60a3258e3816',
                            'metadata' =>
                                [
                                    'version'      => 0,
                                    'subscription' =>
                                        [
                                            0 =>
                                                [
                                                    'topic' => 'kafka-swoole',
                                                ],
                                        ],
                                    'userData'     => '',
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 11,
                ],
            'size'           => 439,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
