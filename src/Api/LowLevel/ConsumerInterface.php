<?php

namespace Kafka\Api\LowLevel;


interface ConsumerInterface
{
    public function handler(int $offset, string $message);
}