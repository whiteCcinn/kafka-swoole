<?php
namespace App\Handler;

use Kafka\Api\HighLevel\ConsumerInterface;

class HighLevelHandler implements ConsumerInterface
{
    public function handler(string $message)
    {
        var_dump($message);
    }
}