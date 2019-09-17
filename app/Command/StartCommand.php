<?php

namespace App\Command;

use Kafka\Enum\ProtocolEnum;
use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Enum\ProtocolVersionEnum;
use Kafka\Protocol\Request\Common\RequestHeader;
use Kafka\Protocol\Request\HeartbeatRequest;
use Kafka\Protocol\Request\ListOffsets\PartitionsListsOffsets;
use Kafka\Protocol\Request\ListOffsets\TopicsListsOffsets;
use Kafka\Protocol\Request\ListOffsetsRequest;
use Kafka\Protocol\Request\Metadata\DataProduce;
use Kafka\Protocol\Request\Metadata\TopicDataProduce;
use Kafka\Protocol\Request\MetadataRequest;
use Kafka\Protocol\Request\ProduceRequest;
use Kafka\Protocol\Response\ListOffsetsResponse;
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
//

//                $protocol = new MetadataRequest();
//                $protocol->setRequestHeader(
//                    (new RequestHeader())->setApiVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
//                                         ->setClientId(String16::value('kafka-php'))
//                                         ->setCorrelationId(Int32::value(ProtocolEnum::METADATA))
//                                         ->setApiKey(Int16::value(ProtocolEnum::METADATA))
//                );
//                $protocol->setTopicName([String16::value('caiwenhui')]);

                $protocol = new ListOffsetsRequest();
                $partitions = [];
                array_push($partitions,
                    (new PartitionsListsOffsets())->setPartition(Int32::value(0))
                                                  ->setMaxNumOffsets(Int32::value(10))
                                                  ->setTimestamp(Int64::value(time()))
                );
                $topics = [];
                array_push($topics,
                    (new TopicsListsOffsets())->setTopic(String16::value('caiwenhui'))
                                              ->setPartitions($partitions)
                );
                $protocol->setRequestHeader(
                    (new RequestHeader())->setApiVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
                                         ->setClientId(String16::value('kafka-swoole'))
                                         ->setCorrelationId(Int32::value(ProtocolEnum::LIST_OFFSETS))
                                         ->setApiKey(Int16::value(ProtocolEnum::LIST_OFFSETS))
                );
                $protocol->setReplicaId(Int32::value(-1));
                $protocol->setTopics($topics);

//                $n = $socket->send($payload);
//                echo 'length:' . $n . PHP_EOL;
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

//                $protocol = new ProduceRequest();
//                $protocol->setRequestHeader(
//                    (new RequestHeader())->setApiVersion(Int16::value(ProtocolVersionEnum::API_VERSION_0))
//                                         ->setClientId(String16::value('kafka-php'))
//                                         ->setCorrelationId(Int32::value(ProtocolEnum::PRODUCE))
//                                         ->setApiKey(Int16::value(ProtocolEnum::PRODUCE))
//                );
//                $protocol->setAcks(Int16::value(0));
//                $protocol->setTimeout(Int32::value(100));
//                $topics = [];
//                array_push($topics,(new TopicDataProduce())->setTopic(String16::value('test'))->setData(
//                    [
//                        (new DataProduce())->setPartition(Int32::value(0))->setRecordSet([
//                            String16::value('test...'),
//                            String16::value('test...'),
//                            String16::value('test...')
//                        ])
//                    ]
//                ));
//                $protocol->setTopicData($topics);
                $payload = $protocol->pack();
                var_dump(bin2hex($payload));
                $n = $socket->send($payload);
                echo 'length:' . $n . PHP_EOL;

                $data = $socket->recv();
                $protocol->response->unpack($data);
                /** @var ListOffsetsResponse $response */
                $response = $protocol->response;
                foreach ($response->getResponses() as $resp) {
                    foreach ($resp->getPartitionResponses() as $resp2) {
                        var_dump(ProtocolErrorEnum::getTextByCode($resp2->getErrorCode()->getValue()));
                    }
                }

                var_dump($protocol->response->toArray());
                var_dump($protocol->response);

                /*
array(3) {
  ["responses"]=>
  array(1) {
    [0]=>
    array(2) {
      ["topic"]=>
      string(9) "caiwenhui"
      ["partitionResponses"]=>
      array(1) {
        [0]=>
        array(3) {
          ["partition"]=>
          int(0)
          ["errorCode"]=>
          int(3)
          ["offsets"]=>
          array(0) {
          }
        }
      }
    }
  }
  ["responseHeader"]=>
  array(1) {
    ["correlationId"]=>
    int(2)
  }
  ["size"]=>
  int(33)
}

object(Kafka\Protocol\Response\MetadataResponse)#46 (4) {
  ["brokers":"Kafka\Protocol\Response\MetadataResponse":private]=>
  array(4) {
    [0]=>
    object(Kafka\Protocol\Response\Metadata\BrokerMetadata)#62 (3) {
      ["nodeId":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#68 (1) {
        ["value":protected]=>
        int(1003)
      }
      ["host":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\String16)#67 (1) {
        ["value":protected]=>
        string(7) "mkafka3"
      }
      ["port":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#69 (1) {
        ["value":protected]=>
        int(9092)
      }
    }
    [1]=>
    object(Kafka\Protocol\Response\Metadata\BrokerMetadata)#70 (3) {
      ["nodeId":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#72 (1) {
        ["value":protected]=>
        int(1004)
      }
      ["host":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\String16)#71 (1) {
        ["value":protected]=>
        string(7) "mkafka4"
      }
      ["port":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#73 (1) {
        ["value":protected]=>
        int(9092)
      }
    }
    [2]=>
    object(Kafka\Protocol\Response\Metadata\BrokerMetadata)#74 (3) {
      ["nodeId":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#76 (1) {
        ["value":protected]=>
        int(1001)
      }
      ["host":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\String16)#75 (1) {
        ["value":protected]=>
        string(7) "mkafka1"
      }
      ["port":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#77 (1) {
        ["value":protected]=>
        int(9092)
      }
    }
    [3]=>
    object(Kafka\Protocol\Response\Metadata\BrokerMetadata)#78 (3) {
      ["nodeId":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#80 (1) {
        ["value":protected]=>
        int(1002)
      }
      ["host":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\String16)#79 (1) {
        ["value":protected]=>
        string(7) "mkafka2"
      }
      ["port":"Kafka\Protocol\Response\Metadata\BrokerMetadata":private]=>
      object(Kafka\Protocol\Type\Int32)#81 (1) {
        ["value":protected]=>
        int(9092)
      }
    }
  }
  ["topics":"Kafka\Protocol\Response\MetadataResponse":private]=>
  array(1) {
    [0]=>
    object(Kafka\Protocol\Response\Metadata\TopicMetadata)#57 (3) {
      ["errorCode":"Kafka\Protocol\Response\Metadata\TopicMetadata":private]=>
      object(Kafka\Protocol\Type\Int16)#84 (1) {
        ["value":protected]=>
        int(0)
      }
      ["name":"Kafka\Protocol\Response\Metadata\TopicMetadata":private]=>
      object(Kafka\Protocol\Type\String16)#83 (1) {
        ["value":protected]=>
        string(9) "caiwenhui"
      }
      ["partitions":"Kafka\Protocol\Response\Metadata\TopicMetadata":private]=>
      array(1) {
        [0]=>
        object(Kafka\Protocol\Response\Metadata\PartitionMetadata)#85 (5) {
          ["errorCode":"Kafka\Protocol\Response\Metadata\PartitionMetadata":private]=>
          object(Kafka\Protocol\Type\Int16)#94 (1) {
            ["value":protected]=>
            int(0)
          }
          ["partitionIndex":"Kafka\Protocol\Response\Metadata\PartitionMetadata":private]=>
          object(Kafka\Protocol\Type\Int32)#93 (1) {
            ["value":protected]=>
            int(0)
          }
          ["leaderId":"Kafka\Protocol\Response\Metadata\PartitionMetadata":private]=>
          object(Kafka\Protocol\Type\Int32)#95 (1) {
            ["value":protected]=>
            int(1004)
          }
          ["replicaNodes":"Kafka\Protocol\Response\Metadata\PartitionMetadata":private]=>
          array(1) {
            [0]=>
            object(Kafka\Protocol\Type\Int32)#96 (1) {
              ["value":protected]=>
              int(1004)
            }
          }
          ["isrNodes":"Kafka\Protocol\Response\Metadata\PartitionMetadata":private]=>
          array(1) {
            [0]=>
            object(Kafka\Protocol\Type\Int32)#97 (1) {
              ["value":protected]=>
              int(1004)
            }
          }
        }
      }
    }
  }
  ["responseHeader":protected]=>
  object(Kafka\Protocol\Response\Common\ResponseHeader)#58 (1) {
    ["correlationId":"Kafka\Protocol\Response\Common\ResponseHeader":private]=>
    object(Kafka\Protocol\Type\Int32)#64 (1) {
      ["value":protected]=>
      int(3)
    }
  }
  ["size":protected]=>
  object(Kafka\Protocol\Type\Int32)#63 (1) {
    ["value":protected]=>
    int(123)
  }
}
                 */
                if (empty($data)) {
                    $socket->close();
                    break;
                }
                \Co::sleep(10.0);
            }
            var_dump($retval, $socket->errCode);
        });
    }
}
