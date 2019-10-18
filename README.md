# kafka-swoole
Implement all kafka protocols, providing 'HighLevel' and 'LowLevel' client apis respectively, and utilize swoole to realize collaboration and flexibly extend consumers' client

> If you would like to contribute code to help me speed up my progress, please contact me at email:471113744@qq.com

core framework：[kafka-swoole-core](https://github.com/whiteCcinn/kafka-swoole-core)

## Install

### by composer

```bash
version=dev-master;composer create-project ccinn/kafka-swoole kafka-swoole ${version}
```

## by git

```bash
git clone https://github.com/whiteCcinn/kafka-swoole.git && cd kafka-swoole && composer install
```
## docker

```bash
docker build -t kafka-swoole:latest .
docker run -it --name kafka-swoole -v $(PWD):/data/www kafka-swoole:latest bash
```

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

### Rpc

Support real-time acquisition of data in runtime, interaction through RPC protocol with AF_UNIX interprocess communication

```
php bin/kafka-client rpc -h
Description:
  Built-in runtime RPC command

Usage:
  rpc <type>

Arguments:
  type                  which you want to execute command?

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  The following are the built-in RPC command options：
  kafka_lag
  offset_checker
  block_size
  member_leader
  metadata_brokers
  metadata_topics
```

- kafka_lag（Check the total difference between the current offset and the maximum offset in kafka）
```bash
php bin/kafka-client rpc kafka_lag
1000
```

- offset_checker(View the details of the current offset and the offset and difference in the kafka service for each partition of the topic)

```bash
php bin/kafka-client rpc offset_checker
 -------------- ----------- ---------------- ------------------ -----------------
  topic          partition   current-offset   kafka-max-offset   remaining-count
 -------------- ----------- ---------------- ------------------ -----------------
  kafka-swoole   2           50223            50223              0
  kafka-swoole   3           70353            70353              0
  kafka-swoole   0           52395            52395              0
  kafka-swoole   1           50407            50407              0
 -------------- ----------- ---------------- ------------------ -----------------
```

- block_size(If you are using storage media in indirect mode, you can use this command to see the current number of storage media)

```bash
php bin/kafka-client rpc block_size
254
```

- block_size(If you are using storage media in indirect mode, you can use this command to see the current number of storage media)

```bash
php bin/kafka-client rpc block_size
254
```

- member_leader（View the Leader of the current consumer group）

```bash
php bin/kafka-client rpc member_leader
 ---------------------------------------------------
  consumer-group-leaderId
 ---------------------------------------------------
  kafka-swoole-4c668caa-c352-4132-9233-ed00942e47e7
 ---------------------------------------------------
```

- metadata_brokers(View available brokers for the kafka service)

```bash
php bin/kafka-client rpc metadata_brokers
 --------- --------- ------
  node-id   host      port
 --------- --------- ------
  1003      mkafka3   9092
  1004      mkafka4   9092
  1001      mkafka1   9092
  1002      mkafka2   9092
 --------- --------- ------
```

- metadata_topicss(See the subscribed topic for more details)

```bash
php bin/kafka-client rpc metadata_topics
 -------------- ----------- ----------- --------------- -----------
  topic          partition   leader-id   replica-nodes   isr-nodes
 -------------- ----------- ----------- --------------- -----------
  kafka-swoole   2           1001        1001,1004       1001,1004
  kafka-swoole   1           1004        1004,1003       1004,1003
  kafka-swoole   3           1002        1002,1001       1002,1001
  kafka-swoole   0           1003        1003,1002       1002,1003
 -------------- ----------- ----------- --------------- -----------
```

### Consumer

`php bin/kafka-client start`

## Config

### Common

#### Options

FILE: `config/common.yaml`

```yaml
# Your kafka version
kafka.version: 0.9.0.0
# This is for bootstrapping and the producer will only use it for getting metadata
# (topics, partitions and replicas). The socket connections for sending the actual data
# will be established based on the broker information returned in the metadata. The
# format is host1:port1,host2:port2, and the list can be a subset of brokers or
# a VIP pointing to a subset of brokers.
metadata.broker.list: "mkafka1:9092,mkafka2:9092,mkafka3:9092,mkafka4:9092"

# The producer generally refreshes the topic metadata from brokers when there is a failure
# (partition missing, leader not available...). It will also poll regularly (default: every 10min
# so 600000ms). If you set this to a negative value, metadata will only get refreshed on failure.
# If you set this to zero, the metadata will get refreshed after each message sent (not recommended)
# Important note: the refresh happen only AFTER the message is sent, so if the producer never sends
# a message the metadata is never refreshed
topic.metadata.refresh.interval.ms: 60 * 10 * 1000

# a string that uniquely identifies a set of consumers within the same consumer group
group.id: "kafka-swoole"

# Which you want to operation
# Example: topic1,topic2,topic3,topic4,topic5
topic.names: "kafka-swoole"

# The periodic time the heartbeat request is sent to tell kafka that the client still exists
heartbeat.interval.ms: 10 * 1000

# The maximum allowed time within a group for which no heartbeat is sent
# So this value must be greater than the heartbeat sending cycle：heartbeat.interval.ms
# (default: 30s)
group.keep.session.max.ms: 30 * 1000

# Select a strategy for assigning partitions to consumer streams. See ProtocolPartitionAssignmentStrategyEnum::class
# Range：
# RoundRobin：
# Sticky：
partition.assignment.strategy: "Range"

# highLevelApi the frequency in ms that the consumer offsets are committed to zookeeper
auto.commit.interval.ms: 10 * 1000

# smallest : automatically reset the offset to the smallest offset
# largest : automatically reset the offset to the largest offset
auto.offset.reset: largest
```

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

## Env

```bash
# APP
APP_NAME=kafka-swoole
## zh_CN,en_US
APP_LANGUGE=en_US

# SERVER
# The server just only receive rpc request select data in memory
SERVER_IP=127.0.0.1
SERVER_PORT=9501
SERVER_REACTOR_NUM=1
SERVER_WORKER_NUM=1
SERVER_MAX_REQUEST=50

# KAFKA_CLIENT_CONSUMER_NUM dynamically changes this parameter based on the partition of the subscribed topic
KAFKA_CLIENT_CONSUMER_NUM=2

# KAFKA_CLIENT
# Client Process
# KAFKA_CLIENT_API_MODE："HIGH_LEVEL" / "LOW LEVEL"
# KAFKA_CLIENT_CONSUMER_NUM: Must be less than the maximum partition in topic
KAFKA_CLIENT_API_MODE=LOW_LEVEL

# REDIS/FILE/DIRECTLY
# If you choose "Directly" mode, the number of processing logical processes is equal to the minimum number of kafka client processes.
# The KAFKA_CUSTOM_PROCESS_NUM parameter is ignored.
# Make sure your consumption logic consumes as much data as possible, otherwise the rate of consumption will be lower than the rate of production.

# The process generated by KAFKA_CUSTOM_PROCESS_NUM gets messages from the storage medium
KAFKA_MESSAGE_STORAGE=REDIS

# Number of message processing processes
KAFKA_SINKER_PROCESS_NUM=2

# Which is your storage redis config
KAFKA_STORAGE_REDIS=POOL_REDIS_0

# Redis stores the persistent key
KAFKA_STORAGE_REDIS_KEY=${APP_NAME}:storage:redis:messages

# Redis persists the maximum number of messages
KAFKA_STORAGE_REDIS_LIMIT=40000

# Redis Pool
# `POOL_REDIS_NUM` is number，which begin offset is 0
POOL_REDIS_NUM=1
POOL_REDIS_0_MAX_NUMBER=5
POOL_REDIS_0_MAX_IDLE=3
POOL_REDIS_0_MIN_IDLE=0
POOL_REDIS_0_HOST=mredis
POOL_REDIS_0_PORT=60379
POOL_REDIS_0_AUTH=uXUxGIyprkel1nYWhCyoCYAT4CNCUW2mXkVcDfhTqetnYSD7
POOL_REDIS_0_DB=0
# other redis config ...


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
