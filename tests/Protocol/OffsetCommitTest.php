<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\OffsetCommitRequest;
use Kafka\Protocol\Request\OffsetCommit\PartitionsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommit\TopicsOffsetCommit;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

final class OffsetCommitTest extends AbstractProtocolTest
{
    /**
     * @var OffsetCommitRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new OffsetCommitRequest();
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var OffsetCommitRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setTopics([
                     (new TopicsOffsetCommit())->setName(String16::value('caiwenhui'))
                                               ->setPartitions([
                                                   (new PartitionsOffsetCommit())->setPartitionIndex(Int32::value(0))
                                                                                 ->setCommittedMetadata(String16::value(''))
                                                                                 ->setCommittedOffset(Int64::value(100))
                                               ])
                 ]);
        $data = $protocol->pack();
        $expected = '000000450008000000000008000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c6500000001000963616977656e687569000000010000000000000000000000640000';
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
        /** @var OffsetCommitRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '0000001d0000000800000001000963616977656e68756900000001000000000000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'topics'         =>
                [
                    0 =>
                        [
                            'name'       => 'caiwenhui',
                            'partitions' =>
                                [
                                    0 =>
                                        [
                                            'partitionIndex' => 0,
                                            'errorCode'      => 0,
                                        ],
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 8,
                ],
            'size'           => 29,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
