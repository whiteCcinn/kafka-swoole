<?php

namespace App\Handler;

use Kafka\Api\LowLevel\ConsumerInterface;

class LowLevelHandler implements ConsumerInterface
{
    public function handler(int $offset, string $message)
    {
        // TODO: Implement handler() method.
    }
}