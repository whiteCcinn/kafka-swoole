<?php
declare(strict_types=1);

namespace Kafka\Storage;

/**
 * Interface StorageInterface
 *
 * @package Kafka\Storage
 */
interface StorageInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function push(array $data = []);

    /**
     * @param int $number
     *
     * @return mixed
     */
    public function pop(int $number = 1);
}