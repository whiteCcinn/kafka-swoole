# kafka-swoole
Implement all kafka protocols, providing 'HighLevel' and 'LowLevel' client apis respectively, and utilize swoole to realize collaboration and flexibly extend consumers' client

> If you would like to contribute code to help me speed up my progress, please contact me at email:471113744@qq.com

## rendering

### A member of the consumer group

```bash
Topic:caiwenhui	PartitionCount:1	ReplicationFactor:1	Configs:
	Topic: caiwenhui	Partition: 0	Leader: 1004	Replicas: 1004	Isr: 1004
```

![kafka-client](http://g.recordit.co/cyQrMHAWae.gif)

![kafka-client-2](http://g.recordit.co/6QFSjl7vSo.gif)

### Multiple members of the consumer group

```bash
Topic:kafka-swoole	PartitionCount:4	ReplicationFactor:2	Configs:
	Topic: kafka-swoole	Partition: 0	Leader: 1003	Replicas: 1003,1002	Isr: 1003,1002
	Topic: kafka-swoole	Partition: 1	Leader: 1004	Replicas: 1004,1003	Isr: 1004,1003
	Topic: kafka-swoole	Partition: 2	Leader: 1001	Replicas: 1001,1004	Isr: 1001,1004
	Topic: kafka-swoole	Partition: 3	Leader: 1002	Replicas: 1002,1001	Isr: 1001,1002
```

- KAFKA_CLIENT_CONSUMER_NUM=2
- KAFKA_CLIENT_CONSUMER_NUM=4

![kafka-client-3](http://g.recordit.co/ReRtQzbYKI.gif)

![kafka-client-4](http://g.recordit.co/PYG4YTwheG.gif)

## Command

### Produce

`php bin/kafka-client kafka.produce [options] [--] <message>`

```bash
php bin/kafka-client kafka.produce --help

Description:
  Send a message

Usage:
  kafka.produce [options] [--] <message>

Arguments:
  message                      The message you wish to send.

Options:
  -t, --topic[=TOPIC]          Which is the topic you want to send?
  -p, --partition[=PARTITION]  Which is the topic you want to send to partition?
  -k, --key[=KEY]              Which is the topic you want to send to partition by key?
  -h, --help                   Display this help message
  -q, --quiet                  Do not output any message
  -V, --version                Display this application version
      --ansi                   Force ANSI output
      --no-ansi                Disable ANSI output
  -n, --no-interaction         Do not ask any interactive question
  -v|vv|vvv, --verbose         Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  This command will help you send separate messages to a topic..
```

### Consumer

`php bin/kafka-client start`

## Config

### Common

#### Options

FILE: `config/high_level.yaml`

|  option   | optional | default  |
|  ----  | ----  | ----  |
| auto.offset.reset  | smallest \| largest | largest |

### Producer

FILE: `config/producer.yaml`

## Protocol API VERSION

|  VERSION   | STATUS  |
|  ----  | ----  |
| 0  | :white_check_mark: |
| 1  | :x: |

## Protocol

- [x] Produce
- [x] Fetch
- [x] ListOffsets
- [x] Metadata
- [ ] LeaderAndIsr
- [ ] StopReplica
- [ ] UpdateMetadata
- [ ] ControlledShutdown
- [x] OffsetCommit
- [x] OffsetFetch
- [x] FindCoordinator
- [x] JoinGroup
- [x] Heartbeat
- [x] Heartbeat
- [x] LeaveGroup
- [x] SyncGroup
- [ ] DescribeGroups
- [ ] ListGroups
- [ ] ListGroups
- [ ] SaslHandshake
- [ ] ApiVersions
- [x] CreateTopics
- [ ] DeleteTopics
- [ ] DeleteRecords
- [ ] InitProducerId
- [ ] OffsetForLeaderEpoch
- [ ] AddPartitionsToTxn
- [ ] AddOffsetsToTxn
- [ ] EndTxn
- [ ] WriteTxnMarkers
- [ ] TxnOffsetCommit
- [ ] DescribeAcls
- [ ] CreateAcls
- [ ] DeleteAcls
- [ ] DescribeConfigs
- [ ] AlterConfigs
- [ ] AlterReplicaLogDirs
- [ ] DescribeLogDirs
- [ ] SaslAuthenticate
- [ ] CreatePartitions
- [ ] CreateDelegationToken
- [ ] RenewDelegationToken
- [ ] ExpireDelegationToken
- [ ] DescribeDelegationToken
- [ ] DeleteGroups
- [ ] ElectPreferredLeaders
- [ ] IncrementalAlterConfigs

### usage

The idea here is that the examples are initiated based on the API protocol, not the client API.
As an example, here we start a request for ListOffsetsRequest.

```php
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

$payload = $protocol->pack();
$n = $socket->send($payload);

$data = $socket->recv();
$protocol->response->unpack($data);
var_dump($protocol->response,$protocol->response->toArray()); // Here you can see the response protocol of the kafka service
/*
object(Kafka\Protocol\Response\ListOffsetsResponse)#46 (3) {
  ["responses":"Kafka\Protocol\Response\ListOffsetsResponse":private]=>
  array(1) {
    [0]=>
    object(Kafka\Protocol\Response\ListOffsets\ResponsesListOffsets)#68 (2) {
      ["topic":"Kafka\Protocol\Response\ListOffsets\ResponsesListOffsets":private]=>
      object(Kafka\Protocol\Type\String16)#72 (1) {
        ["value":protected]=>
        string(9) "caiwenhui"
      }
      ["partitionResponses":"Kafka\Protocol\Response\ListOffsets\ResponsesListOffsets":private]=>
      array(1) {
        [0]=>
        object(Kafka\Protocol\Response\ListOffsets\PartitionsResponsesListOffsets)#71 (3) {
          ["partition":"Kafka\Protocol\Response\ListOffsets\PartitionsResponsesListOffsets":private]=>
          object(Kafka\Protocol\Type\Int32)#79 (1) {
            ["value":protected]=>
            int(0)
          }
          ["errorCode":"Kafka\Protocol\Response\ListOffsets\PartitionsResponsesListOffsets":private]=>
          object(Kafka\Protocol\Type\Int16)#78 (1) {
            ["value":protected]=>
            int(3)
          }
          ["offsets":"Kafka\Protocol\Response\ListOffsets\PartitionsResponsesListOffsets":private]=>
          array(0) {
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
      int(2)
    }
  }
  ["size":protected]=>
  object(Kafka\Protocol\Type\Int32)#63 (1) {
    ["value":protected]=>
    int(33)
  }
}

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
*/
```

## Unit-test

Take the project directory as the root directory.

```
php vendor/bin/phpunit tests/Protocol/
```

```sh
PHPUnit 7.5.16 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.1.28
Configuration: /www5/kafka-swoole/phpunit.xml.dist

......................                                            22 / 22 (100%)

Time: 64 ms, Memory: 6.00 MB

OK (22 tests, 22 assertions)
```

## References

- [Apache.kafka.protocol](http://kafka.apache.org/protocol.html)
- [Kafka.ConsumerConfig](https://github.com/apache/kafka/blob/0.9.0/core/src/main/scala/kafka/consumer/ConsumerConfig.scala)
- [Kafka.ProducerConfig](https://github.com/apache/kafka/blob/0.9.0/core/src/main/scala/kafka/producer/ProducerConfig.scala)
