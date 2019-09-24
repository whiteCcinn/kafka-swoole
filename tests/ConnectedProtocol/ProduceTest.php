<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\Produce\DataProduce;
use Kafka\Protocol\Request\Produce\MessageProduce;
use Kafka\Protocol\Request\Produce\MessageSetProduce;
use Kafka\Protocol\Request\Produce\TopicDataProduce;
use Kafka\Protocol\Request\ProduceRequest;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class ProduceTest extends AbstractProtocolTest
{
    /**
     * @var ProduceRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new ProduceRequest();
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var ProduceRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setAcks(Int16::value(1))
                 ->setTimeout(Int32::value(1 * 1000))
                 ->setTopicData([
                     (new TopicDataProduce())->setTopic(String16::value('caiwenhui'))
                                             ->setData([
                                                 (new DataProduce())->setPartition(Int32::value(0))
                                                                    ->setMessageSet([
                                                                        (new MessageSetProduce())->setOffset(Int64::value(0))
                                                                                                 ->setMessage(
                                                                                                     (new MessageProduce())->setValue(Bytes32::value('test...'))
                                                                                                 ),

                                                                    ])
                                             ])
                 ]);
        $data = $protocol->pack();

        $expected = '000000580000000000000000000c6b61666b612d73776f6f6c650001000003e800000001000963616977656e6875690000000100000000000000210000000000000000000000153c1950a800000000000000000007746573742e2e2e';
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
        /** @var ProduceRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka4', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        $this->assertIsArray($data);
        foreach ($protocol->response->getResponses() as $response) {
            foreach ($response->getPartitionResponses() as $partitionResponse) {
                $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $partitionResponse->getErrorCode()->getValue());
            }
        }
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testMultiEncode()
    {
        /** @var ProduceRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setAcks(Int16::value(1))
                 ->setTimeout(Int32::value(1 * 1000))
                 ->setTopicData([
                     (new TopicDataProduce())->setTopic(String16::value('caiwenhui'))
                                             ->setData([
                                                 (new DataProduce())->setPartition(Int32::value(0))
                                                                    ->setMessageSet([
                                                                        (new MessageSetProduce())->setOffset(Int64::value(0))
                                                                                                 ->setMessage(
                                                                                                     (new MessageProduce())->setValue(Bytes32::value('test1...'))
                                                                                                 ),
                                                                        (new MessageSetProduce())->setOffset(Int64::value(1))
                                                                                                 ->setMessage(
                                                                                                     (new MessageProduce())->setValue(Bytes32::value('test2...'))
                                                                                                 ),

                                                                    ])
                                             ])
                 ]);
        $data = $protocol->pack();

        $expected = '0000007b0000000000000000000c6b61666b612d73776f6f6c650001000003e800000001000963616977656e687569000000010000000000000044000000000000000000000016012658d00000000000000000000874657374312e2e2e0000000000000001000000161393f73e0000000000000000000874657374322e2e2e';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

    /**
     * @author  caiwenhui
     * @depends testMultiEncode
     *
     * @param string $data
     */
    public function testSendMulti(string $data)
    {
        /** @var ProduceRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka4', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        $this->assertIsArray($data);
        foreach ($protocol->response->getResponses() as $response) {
            foreach ($response->getPartitionResponses() as $partitionResponse) {
                $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $partitionResponse->getErrorCode()->getValue());
            }
        }
    }
}
