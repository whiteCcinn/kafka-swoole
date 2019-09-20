<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\FetchRequest;
use Kafka\Protocol\Request\Metadata\PartitionsFetch;
use Kafka\Protocol\Request\Metadata\TopicFetch;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;


final class FetchTest extends AbstractProtocolTest
{
    /**
     * @var FetchRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new FetchRequest();
    }

    /**
     * @author caiwenhui
     * @group  encode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var FetchRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setReplicaId(Int32::value(-1))
                 ->setMaxWaitTime(Int32::value(100))
                 ->setMinBytes(Int32::value(64 * 1024))
                 ->setTopics([
                     (new TopicFetch())->setTopic(String16::value('caiwenhui'))
                                       ->setPartitions([
                                           (new PartitionsFetch())->setPartition(Int32::value(0))
                                                                  ->setFetchOffset(Int64::value(0))
                                                                  ->setPartitionMaxBytes(Int32::value(2 * 1024 * 1024))
                                       ])
                 ]);

        $data = $protocol->pack();
        var_dump($data);exit;

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
        /** @var FetchRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka4', 9092, function () use ($data) {
            return $data;
        }, function (string $data) use ($protocol) {
            $protocol->response->unpack($data);
        });

        $this->assertIsArray($data);
        foreach ($protocol->response->getResponses() as $response) {
            foreach ($response->getPartitionResponses() as $partitionResponse) {
                $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $partitionResponse->getErrorCode()->getValue());
            }
        }
    }

    /**
     * @author  caiwenhui
     * @group   decode
     */
    public function testDecode()
    {

    }

}
