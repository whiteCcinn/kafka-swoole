<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\MetadataRequest;
use KafkaTest\AbstractProtocolTest;


final class MetadataTest extends AbstractProtocolTest
{
    public function setUp(): void
    {
        $this->protocol = new MetadataRequest();
        parent::setUp();
    }
}
