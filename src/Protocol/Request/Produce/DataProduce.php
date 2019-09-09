<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Metadata;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class DataProduce
{

    /**
     * Topic partition id
     *
     * @var Int32 $partition
     */
    private $partition;

    /**
     * null TODO: type must be sure
     *
     * @var $recordSet
     */
    private $recordSet;
}
