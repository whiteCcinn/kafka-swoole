<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;
use Kafka\Protocol\Type\Int32;

class CreateTopicsRequest extends AbstractRequest
{

    private $topics;

    /**
     * @var Int32 $timeoutMs
     */
    private $timeoutMs;
}
