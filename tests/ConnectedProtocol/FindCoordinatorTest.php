<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\FindCoordinatorRequest;
use Kafka\Protocol\Response\FindCoordinatorResponse;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


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
     * @group  connectedEncode
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
     * @param string $data
     *
     * @depends testEncode
     */
    public function testSend(string $data)
    {
        /** @var FindCoordinatorRequest $protocol */
        $protocol = $this->protocol;

        SocketServer::getInstance(true)->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var FindCoordinatorResponse $coordinatorResponse */
        $coordinatorResponse = $protocol->response;
        $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $coordinatorResponse->getErrorCode()->getValue());
    }
}
