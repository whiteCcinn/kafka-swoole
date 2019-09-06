<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class AbstractDecorator.
 */
abstract class AbstractDecorator
{
    abstract public function handler(array $data): array;
}
