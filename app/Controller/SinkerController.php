<?php

namespace App\Controller;


use Kafka\Api\ProducerApi;

class SinkerController
{
    /**
     * @param array $messages
     *
     * @return array
     */
    public static function handler(array $messages): array
    {
        $acks = [];
        foreach ($messages as $k => $info) {
            ['message' => $message] = $info;
            var_dump($message);
            ProducerApi::setBrokerListMap('new','mkafka1:9092');
            ProducerApi::produce('new','kafka-swoole',null,null,$message);
            $acks[$k] = true;
        }

        return $acks;
    }
}