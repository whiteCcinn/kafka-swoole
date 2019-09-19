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

    public function testEncode(): void
    {
        try {
            $test = $this->protocol->pack();
        } catch (\Exception $e) {

        }
    }

    public function testDecode(): void
    {
        $test = $this->protocol->response;

        self::assertSame('00000013001200000000001200096b61666b612d706870', bin2hex($test));
    }

    public function testDynamic()
    {

    }
}
