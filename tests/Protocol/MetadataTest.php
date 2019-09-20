<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;


final class MetadataTest extends AbstractProtocolTest
{
    public function init()
    {
        $this->protocol = new MetadataRequest();
    }


    /**
     * @author caiwenhui
     * @group encode
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
     * @depends testEncode
     * @param string $data
     */
    public function testSend(string $data)
    {
        /** @var MetadataRequest $protocol */
        $protocol = $this->protocol;

        SocketServer::getInstance()->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data) use ($protocol) {
            $protocol->response->unpack($data);
        });

        var_dump($protocol->response->toArray());
        exit;
    }

    public function testDecode(): void
    {
        $test = $this->protocol->response;
    }

    public function testDynamic()
    {

    }
}
