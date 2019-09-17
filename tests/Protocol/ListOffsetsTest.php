<?php
declare(strict_types=1);

namespace KafkaTest\Protocol;

use Kafka\Protocol\Request\ListOffsetsRequest;
use KafkaTest\AbstractProtocolTest;

final class ListOffsetsTest extends AbstractProtocolTest
{
    public function setUp(): void
    {
        $this->protocol = new ListOffsetsRequest();
        parent::setUp();
    }

    public function testPack(): void
    {
        $data     = '00000004000003eb00076d6b61666b613300002384000003ec00076d6b61666b613400002384000003e900076d6b61666b613100002384000003ea00076d6b61666b613200002384000000010000000963616977656e68756900000001000000000000000003ec00000001000003ec00000001000003ec';

        $test = $this->meta->decode(hex2bin($data));

        var_dump($test);exit;

        $data = ['test'];

        $test = $this->meta->encode($data);
        var_dump( bin2hex($test));exit;
        self::assertSame('0000001d000300000000000300096b61666b612d70687000000001000474657374', bin2hex($test));
    }

}
