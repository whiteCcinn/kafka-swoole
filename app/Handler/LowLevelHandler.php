<?php

namespace App\Handler;

use Kafka\Api\LowLevel\ConsumerInterface;

class LowLevelHandler implements ConsumerInterface
{
    public function handler(string $topic, int $partition, int $offset, string $message)
    {
        var_dump($message);
    }
}
