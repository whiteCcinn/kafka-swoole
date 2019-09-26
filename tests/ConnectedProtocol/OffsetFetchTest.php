<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\OffsetFetchRequest;
use Kafka\Protocol\Request\OffsetFetch\PartitionsOffsetFetch;
use Kafka\Protocol\Request\OffsetFetch\TopicsOffsetFetch;
use Kafka\Protocol\Response\OffsetFetchResponse;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class OffsetOffsetFetchTest extends AbstractProtocolTest
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
     * @group  connectedEncode
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
                                                  (new PartitionsOffsetFetch())->setPartition(Int32::value(0))
                                              ])
                 ]);

        $data = $protocol->pack();
        $expected = '0000003b0009000000000009000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c6500000001000963616977656e6875690000000100000000';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

    /**
     * @author  caiwenhui
     * @depends testEncode
     *
     * @param string $data
     */
    public function testSend(string $data)
    {
        /** @var OffsetFetchRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka4', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var OffsetFetchResponse $resp */
        $resp = $protocol->response;
        $this->assertIsArray($data);
        foreach ($resp->getResponses() as $response) {
            foreach ($response->getPartitionResponses() as $partitionResponse) {
                $this->assertEquals(ProtocolErrorEnum::NO_ERROR,
                    $partitionResponse->getErrorCode()->getValue());
            }
        }
    }
}
