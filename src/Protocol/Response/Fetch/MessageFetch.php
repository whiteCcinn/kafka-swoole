<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Fetch;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int8;
use Kafka\Protocol\CommonRequest;

class MessageFetch
{
    /**
     * @var Int32 $crc
     */
    private $crc;

    /**
     * @var Int8 $magicByte
     */
    private $magicByte;

    /**
     * @var Int8 $attributes
     */
    private $attributes;

    /**
     * @var Bytes32 $key
     */
    private $key;

    /**
     * @var Bytes32 $value
     */
    private $value;

    /**
     * @return Int32
     */
    public function getCrc(): Int32
    {
        return $this->crc;
    }

    /**
     * @param Int32 $crc
     *
     * @return MessageFetch
     */
    public function setCrc(Int32 $crc): MessageFetch
    {
        $this->crc = $crc;

        return $this;
    }

    /**
     * @return Int8
     */
    public function getMagicByte(): Int8
    {
        return $this->magicByte;
    }

    /**
     * @param Int8 $magicByte
     *
     * @return MessageFetch
     */
    public function setMagicByte(Int8 $magicByte): MessageFetch
    {
        $this->magicByte = $magicByte;

        return $this;
    }

    /**
     * @return Int8
     */
    public function getAttributes(): Int8
    {
        return $this->attributes;
    }

    /**
     * @param Int8 $attributes
     *
     * @return MessageFetch
     */
    public function setAttributes(Int8 $attributes): MessageFetch
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return Bytes32
     */
    public function getKey(): Bytes32
    {
        return $this->key;
    }

    /**
     * @param Bytes32 $key
     *
     * @return MessageFetch
     */
    public function setKey(Bytes32 $key): MessageFetch
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return Bytes32
     */
    public function getValue(): Bytes32
    {
        return $this->value;
    }

    /**
     * @param Bytes32 $value
     *
     * @return MessageFetch
     */
    public function setValue(Bytes32 $value): MessageFetch
    {
        $this->value = $value;

        return $this;
    }
}
