<?php

namespace App\Controller;


class SinkerController extends AbstractController
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
            $acks[$k] = true;
        }

        return $acks;
    }
}