<?php
declare(strict_types=1);

namespace KafkaTest;

use Kafka\Protocol\Request\ListOffsetsRequest;
use PHPUnit\Framework\TestCase;

abstract class AbstractProtocolTest extends TestCase
{
    /**
     * @var ListOffsetsRequest $protocol
     */
    protected $protocol;

    public function setUp(): void
    {
        if (!$this->protocol instanceof AbstractProtocolTest) {
            $this->setUp();
        }
    }
}
