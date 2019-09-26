<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\OffsetCommit\PartitionsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommit\TopicsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommitRequest;
use Kafka\Protocol\Response\OffsetCommitResponse;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class OffsetCommitTest extends AbstractProtocolTest
{
    /**
     * @var OffsetCommitRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new OffsetCommitRequest();
    }

    /**
     * @author caiwenhui
     * @group  connectedEncode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var OffsetCommitRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setGroupId(String16::value('kafka-swoole'))
                 ->setTopics([
                     (new TopicsOffsetCommit())->setName(String16::value('caiwenhui'))
                                               ->setPartitions([
                                                   (new PartitionsOffsetCommit())->setPartitionIndex(Int32::value(0))
                                                                                 ->setCommittedMetadata(String16::value(''))
                                                                                 ->setCommittedOffset(Int64::value(100))
                                               ])
                 ]);
        $data = $protocol->pack();
        $expected = '000000450008000000000008000c6b61666b612d73776f6f6c65000c6b61666b612d73776f6f6c6500000001000963616977656e687569000000010000000000000000000000640000';
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
        /** @var OffsetCommitRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka2', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        /** @var OffsetCommitResponse $resp */
        $resp = $protocol->response;
        $this->assertIsArray($data);
        foreach ($resp->getTopics() as $topic) {
            foreach ($topic->getPartitions() as $partition) {
                $this->assertEquals(ProtocolErrorEnum::NO_ERROR,
                    $partition->getErrorCode()->getValue());
            }
        }
    }
}
