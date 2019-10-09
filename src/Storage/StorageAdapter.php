<?php
declare(strict_types=1);

namespace Kafka\Storage;

/**
 * Class StorageAdapter
 *
 * @package Kafka\Storage
 */
class StorageAdapter implements StorageInterface
{
    /**
     * @var RedisStorage | RedisStorage $adaptee
     */
    private $adaptee;

    /**
     * StorageAdapter constructor.
     *
     * @param $storage
     */
    function __construct($storage)
    {
        $this->adaptee = $storage;
    }

    /**
     * @param array $data
     *
     * @retrun $this
     */
    public function push(array $data = [])
    {
        $this->adaptee->push($data);

        return $this;
    }

    /**
     * @param int $number
     *
     * @return array
     */
    public function pop(int $number = 1)
    {
        if ($number < 1) {
            return [];
        }

        return $this->adaptee->pop($number);
    }
}