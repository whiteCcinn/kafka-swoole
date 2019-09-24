<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\FetchRequest;
use Kafka\Protocol\Request\Fetch\PartitionsFetch;
use Kafka\Protocol\Request\Fetch\TopicsFetch;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

final class FetchTest extends AbstractProtocolTest
{
    /**
     * @var FetchRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new FetchRequest();
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var FetchRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setReplicaId(Int32::value(-1))
                 ->setMaxWaitTime(Int32::value(100))
                 ->setMinBytes(Int32::value(1000))
                 ->setTopics([
                     (new TopicsFetch())->setTopic(String16::value('caiwenhui'))
                                        ->setPartitions([
                                            (new PartitionsFetch())->setPartition(Int32::value(0))
                                                                   ->setFetchOffset(Int64::value(85))
                                                                   ->setPartitionMaxBytes(Int32::value(65536))
                                        ])
                 ]);

        $data = $protocol->pack();
        $expected = '000000450001000000000001000c6b61666b612d73776f6f6c65ffffffff00000064000003e800000001000963616977656e6875690000000100000000000000000000005500010000';
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
        /** @var FetchRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000000f30000000100000001000963616977656e68756900000001000000000000000000000000005b000000ca0000000000000055000000153c1950a800000000000000000007746573742e2e2e000000000000005600000016012658d00000000000000000000874657374312e2e2e0000000000000057000000161393f73e0000000000000000000874657374322e2e2e0000000000000058000000153c1950a800000000000000000007746573742e2e2e000000000000005900000016012658d00000000000000000000874657374312e2e2e000000000000005a000000161393f73e0000000000000000000874657374322e2e2e';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'responses'      =>
                [
                    0 =>
                        [
                            'topic'               => 'caiwenhui',
                            'partition_responses' =>
                                [
                                    0 =>
                                        [
                                            'partitionHeader' =>
                                                [
                                                    'partition'     => 0,
                                                    'errorCode'     => 0,
                                                    'highWatermark' => 91,
                                                ],
                                            'recordSet'       =>
                                                [
                                                    0 =>
                                                        [
                                                            'offset'         => 85,
                                                            'messageSetSize' => 21,
                                                            'message'        =>
                                                                [
                                                                    'crc'        => 1008292008,
                                                                    'magicByte'  => 0,
                                                                    'attributes' => 0,
                                                                    'key'        => '',
                                                                    'value'      => 'test...',
                                                                ],
                                                        ],
                                                    1 =>
                                                        [
                                                            'offset'         => 86,
                                                            'messageSetSize' => 22,
                                                            'message'        =>
                                                                [
                                                                    'crc'        => 19290320,
                                                                    'magicByte'  => 0,
                                                                    'attributes' => 0,
                                                                    'key'        => '',
                                                                    'value'      => 'test1...',
                                                                ],
                                                        ],
                                                    2 =>
                                                        [
                                                            'offset'         => 87,
                                                            'messageSetSize' => 22,
                                                            'message'        =>
                                                                [
                                                                    'crc'        => 328464190,
                                                                    'magicByte'  => 0,
                                                                    'attributes' => 0,
                                                                    'key'        => '',
                                                                    'value'      => 'test2...',
                                                                ],
                                                        ],
                                                    3 =>
                                                        [
                                                            'offset'         => 88,
                                                            'messageSetSize' => 21,
                                                            'message'        =>
                                                                [
                                                                    'crc'        => 1008292008,
                                                                    'magicByte'  => 0,
                                                                    'attributes' => 0,
                                                                    'key'        => '',
                                                                    'value'      => 'test...',
                                                                ],
                                                        ],
                                                    4 =>
                                                        [
                                                            'offset'         => 89,
                                                            'messageSetSize' => 22,
                                                            'message'        =>
                                                                [
                                                                    'crc'        => 19290320,
                                                                    'magicByte'  => 0,
                                                                    'attributes' => 0,
                                                                    'key'        => '',
                                                                    'value'      => 'test1...',
                                                                ],
                                                        ],
                                                    5 =>
                                                        [
                                                            'offset'         => 90,
                                                            'messageSetSize' => 22,
                                                            'message'        =>
                                                                [
                                                                    'crc'        => 328464190,
                                                                    'magicByte'  => 0,
                                                                    'attributes' => 0,
                                                                    'key'        => '',
                                                                    'value'      => 'test2...',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 1,
                ],
            'size'           => 243,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

}
