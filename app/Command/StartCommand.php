<?php

namespace App\Command;

use Kafka\Protocol\Request\Common\RequestHeader;
use Kafka\Protocol\Request\ListOffsets\PartitionsListsOffsets;
use Kafka\Protocol\Request\ListOffsets\TopicsListsOffsets;
use Kafka\Protocol\Request\ListOffsetsRequest;
use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartCommand extends Command
{
    protected static $defaultName = 'start';

    protected function configure()
    {
        $this
            ->setDescription('Start Kafka-Swoole')
            ->setHelp('This command allows Start Kafka-Swoole-Server...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $socket = new \Co\Socket(AF_INET, SOCK_STREAM, 0);

        go(function () use ($socket) {
            // 主进程逻辑（监控子进程/控制进程数）
            $retval = $socket->connect('mkafka1', 9092);
            while ($retval) {
                $n = $socket->send("hello");
                echo 'length:' . $n . PHP_EOL;

                $protocol = new ListOffsetsRequest();
                $partitions = [];
                array_push($partitions,
                    (new PartitionsListsOffsets())->setPartition(Int32::value(0))
                                                  ->setMaxNumOffsets(Int32::value(200))
                                                  ->setTimestamp(Int64::value(time()))
                );
                $topics = [];
                array_push($topics,
                    (new TopicsListsOffsets())->setTopic(String16::value('caiwenhui'))
                                              ->setPartitions($partitions)
                );
                $protocol->setRequestHeader(
                    (new RequestHeader())->setApiVersion(Int16::value('0.9.0.0'))
                                         ->setClientId(String16::value('kafka-swoole'))
                                         ->setCorrelationId(Int32::value(-1))
                );
                $protocol->setReplicaId(Int32::value(-1));
                $protocol->setTopics($topics);
                var_dump($protocol->pack());

                $data = $socket->recv();
                var_dump($data);

                if (empty($data)) {
                    $socket->close();
                    break;
                }
                co::sleep(1.0);
            }
            var_dump($retval, $socket->errCode);
        });
    }
}
