<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\FindCoordinatorRequest;
use Kafka\Protocol\Type\String16;
use KafkaTest\AbstractProtocolTest;


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
     * @author  caiwenhui
     * @group   decode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testDecode()
    {
        /** @var FindCoordinatorRequest $protocol */
        $protocol = $this->protocol;
        $buffer = '000000170000000a0000000003ea00076d6b61666b613200002384';

        $response = $protocol->response;
        $response->unpack(hex2bin($buffer));

        $expected = [
            'errorCode'      => 0,
            'nodeId'         => 1002,
            'host'           => 'mkafka2',
            'port'           => 9092,
            'responseHeader' =>
                [
                    'correlationId' => 10,
                ],
            'size'           => 23,
        ];
        $this->assertEquals($expected, $response->toArray());
    }
}
