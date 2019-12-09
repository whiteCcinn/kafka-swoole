<?php

namespace App\Controller;

use Kafka\Enum\StorageOffsetCommitTypeEnum;

class SinkerController extends AbstractController
{
    /**
     * @param array $messages
     *
     * @return array
     */
    public static function handler(array $messages): array
    {
        foreach ($messages as $k => $info) {
            ['message' => $message] = $info;
            var_dump($message);
        }

        $success = true;
        return ['type' => StorageOffsetCommitTypeEnum::AUTO, 'success' => $success];
    }
}