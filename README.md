# kafka-swoole
Implement all kafka protocols, providing 'HighLevel' and 'LowLevel' client apis respectively, and utilize swoole to realize collaboration and flexibly extend consumers' client

> If you would like to contribute code to help me speed up my progress, please contact me at email:471113744@qq.com

## Client API

### HighLevel

#### Options

FILE: `app/Config/high_level.yaml`

|  option   | optional | default  |
|  ----  | ----  | ----  |
| auto.offset.reset  | smallest \| largest | largest |

### LowLevel

FILE: `app/Config/low_level.yaml`

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
- [ ] CreateTopics
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

## References

- [Apache.kafka.protocol](http://kafka.apache.org/protocol.html)
- [Kafka.client.options](https://github.com/apache/kafka/blob/0.9.0/core/src/main/scala/kafka/consumer/ConsumerConfig.scala)