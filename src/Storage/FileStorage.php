<?php
declare(strict_types=1);

namespace Kafka\Storage;

/**
 * Class FileStorage
 *
 * @package Kafka\Storage
 */
class FileStorage
{
    /**
     * @param array $data
     *
     * @return FileStorage
     */
    public function push(array $data = []): self
    {

    }

    /**
     * @param int $number
     *
     * @return array
     */
    public function pop(int $number = 1): array
    {

    }
}