<?php
declare(strict_types=1);

namespace Kafka\Exception;
use Exception;

class BaseException
{
    public static $exception_function_name = 'handler';

    public function handler(Exception $ex): void
    {
        var_dump($ex->getMessage());
    }
}