<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\OffsetFetchRequest;
use Kafka\Protocol\Request\OffsetFetch\PartitionsOffsetFetch;
use Kafka\Protocol\Request\OffsetFetch\TopicsOffsetFetch;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

final class OffsetFetchTest extends AbstractProtocolTest
{
    /**
     * @var OffsetFetchRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new OffsetFetchRequest();
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var OffsetFetchRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setTopics([
                     (new TopicsOffsetFetch())->setTopic(String16::value('caiwenhui'))
                                              ->setPartitions([
                                                  (new PartitionsOffsetFetch())->setPartition(Int32::value('0'))
                                              ])
                 ]);

        $data = $protocol->pack();
        $expected = '0000003b0009000000000009000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c6500000001000963616977656e6875690000000100000000';
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
        /** @var OffsetFetchRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000000270000000900000001000963616977656e6875690000000100000000ffffffffffffffff00000003';

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
                                            'offset'    => -1,
                                            'metadata'  => '',
                                            'errorCode' => 3,
                                        ],
                                ],
                        ],
                ],
            'responseHeader' =>
                [
                    'correlationId' => 9,
                ],
            'size'           => 39,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

}
