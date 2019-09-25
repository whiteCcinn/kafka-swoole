<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\ListOffsets\PartitionsListsOffsets;
use Kafka\Protocol\Request\ListOffsets\TopicsListsOffsets;
use Kafka\Protocol\Request\ListOffsetsRequest;
use Kafka\Protocol\Response\ListOffsetsResponse;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class ListOffsetsTest extends AbstractProtocolTest
{
    /**
     * @var ListOffsetsRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new ListOffsetsRequest();
    }

    /**
     * @author caiwenhui
     * @group  connectedEncode
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
                                                                        ->setMaxNumOffsets(Int64::value(100000))
                                      ])
        ])->setReplicaId(Int32::value(-1));

        $data = $protocol->pack();
        $expected = '000000450001000000000001000c6b61666b612d73776f6f6c65ffffffff00000064000003e800000001000963616977656e6875690000000100000000000000000000000000010000';
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
        /** @var ListOffsetsRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka1', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var ListOffsetsResponse $response */
        $response = $protocol->response;

        var_export($response->toArray());
        var_dump($response->getCompleteProtocol());

//        $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $response->getErrorCode()->getValue());

//        $this->assertIsArray($data);
//        foreach ($protocol->response->getResponses() as $response) {
//            foreach ($response->getPartitionResponses() as $partitionResponse) {
//                $this->assertEquals(ProtocolErrorEnum::NO_ERROR,
//                    $partitionResponse->getPartitionHeader()->getErrorCode()->getValue());
//            }
//        }
    }
}
