<?php

namespace Kafka\Api\HighLevel;


interface ConsumerInterface
{
    public function handler(string $message);
}