<?php

namespace App\Command;

use Kafka\Enum\ProtocolEnum;
use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\Common\RequestHeader;
use Kafka\Protocol\Request\HeartbeatRequest;
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
                    (new RequestHeader())->setApiVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                         ->setClientId(String16::value('kafka-php'))
                                         ->setCorrelationId(Int32::value(ProtocolEnum::LIST_OFFSETS))
                                         ->setApiKey(Int16::value(ProtocolEnum::LIST_OFFSETS))
                );
                $protocol->setSize(Int32::value(strlen('kafka-swoole')));
                $protocol->setReplicaId(Int32::value(-1));
                $protocol->setTopics($topics);
                $payload = $protocol->pack();
                var_dump(bin2hex($payload));

                $n = $socket->send($payload);
                echo 'length:' . $n . PHP_EOL;
//                $protocol = new HeartbeatRequest;
//                $protocol->setGenerationId(Int32::value(2));
//                $protocol->setGroupId(String16::value('test'));
//                $protocol->setMemberId(String16::value('kafka-php-0e7cbd33-7950-40af-b691-eceaa665d297'));
//                $protocol->setRequestHeader(
//                    (new RequestHeader())->setApiVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
//                                         ->setClientId(String16::value('kafka-php'))
//                                         ->setCorrelationId(Int32::value(ProtocolEnum::HEARTBEAT))
//                                         ->setApiKey(Int16::value(ProtocolEnum::HEARTBEAT))
//                );
//                $expected = '0000004d000c00000000000c00096b61666b612d70687000047465737400000002002e6b61666b612d7068702d30653763626433332d373935302d343061662d623639312d656365616136363564323937';
//                var_dump($expected);
//                var_dump(bin2hex($protocol->pack()));

                $data = $socket->recv();
                $protocol->response->unpack($data);
                var_dump($data);

                if (empty($data)) {
                    $socket->close();
                    break;
                }
                \Co::sleep(1.0);
            }
            var_dump($retval, $socket->errCode);
        });
    }
}
