<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

final class MetadataTest extends AbstractProtocolTest
{
    /**
     * @var MetadataRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new MetadataRequest();
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
        /** @var MetadataRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setTopicName([String16::value('caiwenhui')]);
        $data = $protocol->pack();

        $expected = '000000250003000000000003000c6b61666b612d73776f6f6c6500000001000963616977656e687569';
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

        /** @var MetadataRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '0000007b0000000300000004000003eb00076d6b61666b613300002384000003ec00076d6b61666b613400002384000003e900076d6b61666b613100002384000003ea00076d6b61666b613200002384000000010000000963616977656e68756900000001000000000000000003ec00000001000003ec00000001000003ec';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'brokers'        =>
                [
                    0 =>
                        [
                            'nodeId' => 1003,
                            'host'   => 'mkafka3',
                            'port'   => 9092,
                        ],
                    1 =>
                        [
                            'nodeId' => 1004,
                            'host'   => 'mkafka4',
                            'port'   => 9092,
                        ],
                    2 =>
                        [
                            'nodeId' => 1001,
                            'host'   => 'mkafka1',
                            'port'   => 9092,
                        ],
                    3 =>
                        [
                            'nodeId' => 1002,
                            'host'   => 'mkafka2',
                            'port'   => 9092,
                        ],
                ],
            'topics'         =>
                [
                    0 =>
                        [
                            'errorCode'  => 0,
                            'name'       => 'caiwenhui',
                            'partitions' =>
                                [
                                    0 =>
                                        [
                                            'errorCode'      => 0,
                                            'partitionIndex' => 0,
                                            'leaderId'       => 1004,
                                            'replicaNodes'   =>
                                                [
                                                    0 => 1004,
                                                ],
                                            'isrNodes'       =>
                                                [
                                                    0 => 1004,
                                                ],
                                        ],
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 3,
                ],
            'size'           => 123,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
