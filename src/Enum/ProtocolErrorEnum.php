<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class ProtocolErrorEnum
 *
 * @package Kafka\Enum
 */
class ProtocolErrorEnum extends AbstractEnum
{
    /**
     * @message("Success")
     */
    public const NO_ERROR = 0;

    /**
     * @message("The server experienced an unexpected error when processing the request.")
     */
    public const ERROR_UNKNOWN = -1;

    /**
     * @message("The requested offset is not within the range of offsets maintained by the server.")
     */
    public const OFFSET_OUT_OF_RANGE = 1;

    /**
     * @message("This message has failed its CRC checksum, exceeds the valid size, has a null key for a compacted
     *                topic, or is otherwise corrupt.")
     */
    public const INVALID_MESSAGE = 2;

    /**
     * @message("This server does not host this topic-partition.")
     */
    public const UNKNOWN_TOPIC_OR_PARTITION = 3;

    /**
     * @message("The requested fetch size is invalid.")
     */
    public const INVALID_MESSAGE_SIZE = 4;

    /**
     * @message("There is no leader for this topic-partition as we are in the middle of a leadership election.")
     */
    public const LEADER_NOT_AVAILABLE = 5;

    /**
     * @message("This server is not the leader for that topic-partition.")
     */
    public const NOT_LEADER_FOR_PARTITION = 6;

    /**
     * @message("The request timed out.")
     */
    public const REQUEST_TIMED_OUT = 7;

    /**
     * @message("The broker is not available.")
     */
    public const BROKER_NOT_AVAILABLE = 8;

    /**
     * @message("The replica is not available for the requested topic-partition.")
     */
    public const REPLICA_NOT_AVAILABLE = 9;

    /**
     * @message("The request included a message larger than the max message size the server will accept.")
     */
    public const MESSAGE_SIZE_TOO_LARGE = 10;

    /**
     * @message("The controller moved to another broker.")
     */
    public const STALE_CONTROLLER_EPOCH = 11;

    /**
     * @message("The metadata field of the offset request was too large.")
     */
    public const OFFSET_METADATA_TOO_LARGE = 12;

    /**
     * @message("The server disconnected before a response was received.")
     */
    public const NETWORK_EXCEPTION = 13;

    /**
     * @message("The coordinator is loading and hence can't process requests.")
     */
    public const GROUP_LOAD_IN_PROGRESS = 14;

    /**
     * @message("The coordinator is not available.")
     */
    public const GROUP_COORDINATOR_NOT_AVAILABLE = 15;

    /**
     * @message("This is not the correct coordinator.")
     */
    public const NOT_COORDINATOR_FOR_GROUP = 16;

    /**
     * @message("The request attempted to perform an operation on an invalid topic.")
     */
    public const INVALID_TOPIC = 17;

    /**
     * @message("The request included message batch larger than the configured segment size on the server.")
     */
    public const RECORD_LIST_TOO_LARGE = 18;

    /**
     * @message("Messages are rejected since there are fewer in-sync replicas than required.")
     */
    public const NOT_ENOUGH_REPLICAS = 19;

    /**
     * @message("Messages are written to the log, but to fewer in-sync replicas than required.")
     */
    public const NOT_ENOUGH_REPLICAS_AFTER_APPEND = 20;

    /**
     * @message("Produce request specified an invalid value for required acks.")
     */
    public const INVALID_REQUIRED_ACKS = 21;

    /**
     * @message("Specified group generation id is not valid.")
     */
    public const ILLEGAL_GENERATION = 22;

    /**
     * @message("The group member's supported protocols are incompatible with those of existing members or first group
     *               member tried to join with empty protocol type or empty protocol list.")
     */
    public const INCONSISTENT_GROUP_PROTOCOL = 23;

    /**
     * @message("The configured groupId is invalid.")
     */
    public const INVALID_GROUP_ID = 24;

    /**
     * @message("The coordinator is not aware of this member.")
     */
    public const UNKNOWN_MEMBER_ID = 25;

    /**
     * @message("The session timeout is not within the range allowed by the broker [as configured by
     *               group.min.session.timeout.ms and group.max.session.timeout.ms).")
     */
    public const INVALID_SESSION_TIMEOUT = 26;

    /**
     * @message("The group is rebalancing, so a rejoin is needed.")
     */
    public const REBALANCE_IN_PROGRESS = 27;

    /**
     * @message("The committing offset data size is not valid.")
     */
    public const INVALID_COMMIT_OFFSET_SIZE = 28;

    /**
     * @message("Not authorized to access topics: [Topic authorization failed.]")
     */
    public const TOPIC_AUTHORIZATION_FAILED = 29;

    /**
     * @message("Not authorized to access group: Group authorization failed.")
     */
    public const GROUP_AUTHORIZATION_FAILED = 30;

    /**
     * @message("Cluster authorization failed.")
     */
    public const CLUSTER_AUTHORIZATION_FAILED = 31;

    /**
     * @message("The timestamp of the message is out of acceptable range.")
     */
    public const INVALID_TIMESTAMP = 32;

    /**
     * @message("The broker does not support the requested SASL mechanism.")
     */
    public const UNSUPPORTED_SASL_MECHANISM = 33;

    /**
     * @message("Request is not valid given the current SASL state.")
     */
    public const ILLEGAL_SASL_STATE = 34;

    /**
     * @message("The version of API is not supported.")
     */
    public const UNSUPPORTED_VERSION = 35;

    /**
     * @message("Topic with this name already exists.")
     */
    public const TOPIC_ALREADY_EXISTS = 36;

    /**
     * @message("Number of partitions is below 1.")
     */
    public const INVALID_PARTITIONS = 37;

    /**
     * @message("Replication factor is below 1 or larger than the number of available brokers.")
     */
    public const INVALID_REPLICATION_FACTOR = 38;

    /**
     * @message("Replica assignment is invalid.")
     */
    public const INVALID_REPLICA_ASSIGNMENT = 39;

    /**
     * @message("Configuration is invalid.")
     */
    public const INVALID_CONFIG = 40;

    /**
     * @message("This is not the correct controller for this cluster.")
     */
    public const NOT_CONTROLLER = 41;

    /**
     * @message("This most likely occurs because of a request being malformed by the client library or the message was
     *                sent to an incompatible broker. See the broker logs for more details.")
     */
    public const INVALID_REQUEST = 42;

    /**
     * @message("The message format version on the broker does not support the request.")
     */
    public const UNSUPPORTED_FOR_MESSAGE_FORMAT = 43;

    /**
     * @message("Request parameters do not satisfy the configured policy.")
     */
    public const POLICY_VIOLATION = 44;

    /**
     * @message("The broker received an out of order sequence number.")
     */
    public const OUT_OF_ORDER_SEQUENCE_NUMBER = 45;

    /**
     * @message("The broker received a duplicate sequence number.")
     */
    public const DUPLICATE_SEQUENCE_NUMBER = 46;

    /**
     * @message("Producer attempted an operation with an old epoch. Either there is a newer producer with the same
     *                    transactionalId, or the producer's transaction has been expired by the broker.")
     */
    public const INVALID_PRODUCER_EPOCH = 47;

    /**
     * @message("The producer attempted a transactional operation in an invalid state.")
     */
    public const INVALID_TXN_STATE = 48;

    /**
     * @message("The producer attempted to use a producer id which is not currently assigned to its transactional id.")
     */
    public const INVALID_PRODUCER_ID_MAPPING = 49;

    /**
     * @message("The transaction timeout is larger than the maximum value allowed by the broker (as configured by
     *               transaction.max.timeout.ms).")
     */
    public const INVALID_TRANSACTION_TIMEOUT = 50;

    /**
     * @message("The producer attempted to update a transaction while another concurrent operation on the same
     *               transaction was ongoing.")
     */
    public const CONCURRENT_TRANSACTIONS = 51;

    /**
     * @message("Indicates that the transaction coordinator sending a WriteTxnMarker is no longer the current
     *                     coordinator for a given producer.")
     */
    public const TRANSACTION_COORDINATOR_FENCED = 52;

    /**
     * @message("Transactional Id authorization failed.")
     */
    public const TRANSACTIONAL_ID_AUTHORIZATION_FAILED = 53;

    /**
     * @message("Security features are disabled.")
     */
    public const SECURITY_DISABLED = 54;

    /**
     * @message("The broker did not attempt to execute this operation. This may happen for batched RPCs where some
     *               operations in the batch failed, causing the broker to respond without trying the rest.")
     */
    public const OPERATION_NOT_ATTEMPTED = 55;

    /**
     * @message("Disk error when trying to access log file on the disk.")
     */
    public const KAFKA_STORAGE_ERROR = 56;

    /**
     * @message("The user-specified log directory is not found in the broker config.")
     */
    public const LOG_DIR_NOT_FOUND = 57;

    /**
     * @message("SASL Authentication failed.")
     */
    public const SASL_AUTHENTICATION_FAILED = 58;

    /**
     * @message("False	This exception is raised by the broker if it could not locate the producer metadata
     *                 associated with the producerId in question. This could happen if, for instance, the producer's
     *                 records were deleted because their retention time had elapsed. Once the last records of the
     *                 producerId are removed, the producer's metadata is removed from the broker, and future appends
     *                 by the producer will return this exception.")
     */
    public const UNKNOWN_PRODUCER_ID = 59;

    /**
     * @message("A partition reassignment is in progress.")
     */
    public const REASSIGNMENT_IN_PROGRESS = 60;
}