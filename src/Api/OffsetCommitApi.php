<?php

namespace Kafka\Api;

use App\App;
use Kafka\ClientKafka;
use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Exception\RequestException\OffsetCommitRequestException;
use Kafka\Kafka;
use Kafka\Protocol\Request\OffsetCommit\PartitionsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommit\TopicsOffsetCommit;
use Kafka\Protocol\Request\OffsetCommitRequest;
use Kafka\Protocol\Response\OffsetCommitResponse;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;

class OffsetCommitApi
{
    /**
     * @param string $topic
     * @param int    $partition
     * @param int    $offset
     *
     * @throws OffsetCommitRequestException
     */
    public static function topicPartitionOffsetCommit(string $topic, int $partition, int $offset)
    {
        $offsetCommitRequest = new OffsetCommitRequest();

        $offsetCommitRequest->setTopics([(new TopicsOffsetCommit())->setName(String16::value($topic))->setPartitions([
            (new PartitionsOffsetCommit())->setPartitionIndex(Int32::value($partition))
                                          ->setCommittedOffset(Int64::value($offset))
                                          ->setCommittedMetadata(String16::value(''))
        ])])->setGroupId(String16::value(App::$commonConfig->getGroupId()));
        $leaderId = Kafka::getInstance()->getTopicsPartitionLeaderByTopicAndPartition($topic, $partition);
        $socket = Kafka::getInstance()->getSocketByNodeId($leaderId);
        $data = $offsetCommitRequest->pack();
        $socket->send($data);
        $socket->revcByKafka($offsetCommitRequest);

        /** @var OffsetCommitResponse $response */
        $response = $offsetCommitRequest->response;
        foreach ($response->getTopics() as $topic) {
            foreach ($topic->getPartitions() as $partition) {
                if ($partition->getErrorCode()->getValue() !== ProtocolErrorEnum::NO_ERROR) {
                    throw new OffsetCommitRequestException(sprintf('OffsetCommitRequest request error, the error message is: %s',
                        ProtocolErrorEnum::getTextByCode($partition->getErrorCode()->getValue())));
                }
            }
        }
    }
}