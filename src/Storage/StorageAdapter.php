<?php
declare(strict_types=1);

namespace Kafka\Storage;

/**
 * Class StorageAdapter
 *
 * @package Kafka\Storage
 */
class StorageAdapter
{
    /**
     * @var RedisStorage | RedisStorage $adaptee
     */
    private $adaptee;

    /**
     * StorageAdapter constructor.
     *
     * @param string $className
     */
    function __construct(string $className)
    {
        $this->adaptee = new $className;
    }

    /**
     * @param array $data
     *
     * @return StorageAdapter
     */
    public function push(array $data = []): self
    {
        $this->adaptee->push($data);

        return $this;
    }

    /**
     * @param int $number
     *
     * @return array
     */
    public function pop(int $number = 1): array
    {
        if ($number < 1) {
            return [];
        }

        return $this->adaptee->pop($number);
    }
}