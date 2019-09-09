<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractRequestOrResponse;

class ProduceResponse extends AbstractRequestOrResponse
{
    /**
     * @var
     */
    private $reponses;
}
