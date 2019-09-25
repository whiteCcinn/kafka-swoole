<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\HeartbeatRequest;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;

final class HeartbeatTest extends AbstractProtocolTest
{
    /**
     * @var HeartbeatRequest $protocol
     */
    private $protocol;

    /**
     *
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new HeartbeatRequest();
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
        /** @var HeartbeatRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setGenerationId(Int32::value(1))
                 ->setMemberId(String16::value('kafka-swoole-644e2f2d-2366-4ad8-82c4-59d2804ed6b9'))
                 ->setGroupId(String16::value('kafka-swoole'));
        $data = $protocol->pack();

        $expected = '0000005b000c00000000000c000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c650000000100316b61666b612d73776f6f6c652d36343465326632642d323336362d346164382d383263342d353964323830346564366239';
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

        /** @var HeartbeatRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000000060000000c0000';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'errorCode'      => 0,
            'responseHeader' =>
                [
                    'correlationId' => 12,
                ],
            'size'           => 6,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
