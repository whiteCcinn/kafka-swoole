<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;

class PartitionsOffsetCommit
{
    /**
     * The partition index.
     *
     * @var Int32 $partitionIndex
     */
    private $partitionIndex;

    /**
     * The message offset to be committed.
     *
     * @var Int64 $committedOffset
     */
    private $committedOffset;

    /**
     * 	Any associated metadata the client wants to keep.
     *
     * @var String16 $committedMetadata
     */
    private $committedMetadata;
}
