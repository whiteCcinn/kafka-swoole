<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Fetch;

use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

class PartitionResponsesFetch
{
    /**
     * @var PartitionHeaderFetch $partitionHeader
     */
    private $partitionHeader;

    /**
     * // TODO: type must be sure
     * @var $recordSet
     */
    private $recordSet;
}
