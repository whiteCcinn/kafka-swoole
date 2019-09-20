<?php
declare(strict_types=1);

namespace KafkaTest;

use Kafka\Protocol\Request\ListOffsetsRequest;
use PHPUnit\Framework\TestCase;

abstract class AbstractProtocolTest extends TestCase
{
    public function setUp(): void
    {

    }

    protected function init()
    {
    }
}
