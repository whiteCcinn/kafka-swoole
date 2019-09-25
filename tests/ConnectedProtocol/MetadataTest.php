<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Exception\ProtocolException\MetadataException;
use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Response\MetadataResponse;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


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
     * @group  connectedEncode
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
     * @param string $data
     *
     * @author  caiwenhui
     * @depends testEncode
     *
     * @throws MetadataException
     */
    public function testSend(string $data)
    {
        /** @var MetadataRequest $protocol */
        $protocol = $this->protocol;

        SocketServer::getInstance()->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var MetadataResponse $response */
        $response = $protocol->response;
        if (count($response->getBrokers()) === 0) {
            throw new MetadataException('Brokers is empty, no valid broker');
        }

        if (count($response->getTopics()) === 0) {
            throw new MetadataException('Topics is empty, no valid topic');
        }

        foreach ($response->getTopics() as $topic) {
            $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $topic->getErrorCode()->getValue());
        }
    }
}
