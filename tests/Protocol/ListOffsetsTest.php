<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\ListOffsets\PartitionsListsOffsets;
use Kafka\Protocol\Request\ListOffsets\TopicsListsOffsets;
use Kafka\Protocol\Request\ListOffsetsRequest;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

final class ListOffsetsTest extends AbstractProtocolTest
{
    /**
     * @var ListOffsetsRequest $protocol
     */
    private $protocol;

    /**
     *
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new ListOffsetsRequest();
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
        /** @var ListOffsetsRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setTopics([
            (new TopicsListsOffsets())->setTopic(String16::value('caiwenhui'))
                                      ->setPartitions([
                                          (new PartitionsListsOffsets())->setPartition(Int32::value(0))
                                                                        ->setTimestamp(Int64::value(-1))
                                                                        ->setMaxNumOffsets(Int32::value(100000))
                                      ])
        ])->setReplicaId(Int32::value(-1));

        $data = $protocol->pack();

        $expected = '0000003d0002000000000002000c6b61666b612d73776f6f6c65ffffffff00000001000963616977656e6875690000000100000000ffffffffffffffff000186a0';
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

        /** @var ListOffsetsRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000000310000000200000001000963616977656e6875690000000100000000000000000002000000000000006b0000000000000000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'responses'      =>
                [
                    0 =>
                        [
                            'topic'              => 'caiwenhui',
                            'partitionResponses' =>
                                [
                                    0 =>
                                        [
                                            'partition' => 0,
                                            'errorCode' => 0,
                                            'offsets'   =>
                                                [
                                                    0 => 107,
                                                    1 => 0,
                                                ],
                                        ],
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 2,
                ],
            'size'           => 49,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
