<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\SyncGroup\GroupAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroup\MemberAssignmentsSyncGroup;
use Kafka\Protocol\Request\SyncGroupRequest;
use Kafka\Protocol\Response\SyncGroup\PartitionAssignmentsSyncGroup;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

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
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        $protocol = new SyncGroupRequest();
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setGenerationId(Int32::value(1))
                 ->setMemberId(String16::value('kafka-swoole-f6a5581f-4308-446b-8928-d9a45d48def3'))
                 ->setAssignments([
                     (new GroupAssignmentsSyncGroup())->setMemberId(String16::value('kafka-swoole-f6a5581f-4308-446b-8928-d9a45d48def3'))
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
        $expected = '000000b3000e00000000000e000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c650000000100316b61666b612d73776f6f6c652d66366135353831662d343330382d343436622d383932382d6439613435643438646566330000000100316b61666b612d73776f6f6c652d66366135353831662d343330382d343436622d383932382d6439613435643438646566330000001d000000000001000963616977656e687569000000010000000000000000';
        $this->assertSame($expected, bin2hex($data));
    }

    /**
     * @author  caiwenhui
     * @group   decode
     */
    public function testDecode()
    {
        /** @var SyncGroupRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000000270000000e00000000001d000000000001000963616977656e687569000000010000000000000000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'errorCode'      => 0,
            'assignment'     =>
                [
                    'version'             => 0,
                    'partitionAssignment' =>
                        [
                            0 =>
                                [
                                    'topic'     => 'caiwenhui',
                                    'partition' =>
                                        [
                                            0 => 0,
                                        ],
                                ],
                        ],
                    'userData'            => '',
                ],
            'responseHeader' =>
                [
                    'correlationId' => 14,
                ],
            'size'           => 39,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
