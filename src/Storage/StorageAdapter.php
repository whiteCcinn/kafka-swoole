<?php
declare(strict_types=1);

namespace Kafka\Storage;

use Kafka\Support\SingletonTrait;

/**
 * Class StorageAdapter
 *
 * @package Kafka\Storage
 */
class StorageAdapter implements StorageInterface
{
    use SingletonTrait;
    /**
     * @var RedisStorage | RedisStorage $adaptee
     */
    private $adaptee;

    /**
     * @return RedisStorage
     */
    public function getAdaptee(): RedisStorage
    {
        return $this->adaptee;
    }

    /**
     * @param RedisStorage $adaptee
     *
     * @return StorageAdapter
     */
    public function setAdaptee(RedisStorage $adaptee): StorageAdapter
    {
        if ($this->adaptee === null) {
            $this->adaptee = $adaptee;
        }

        return $this;
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

    /**
     * @param string $message
     *
     * @throws \Exception
     */
    public function ack(string $message)
    {
        $this->adaptee->ack($message);
    }
}