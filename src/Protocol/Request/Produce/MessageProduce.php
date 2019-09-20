<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\Produce;

use Kafka\Protocol\Type\Bytes32;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int8;
use Kafka\Protocol\CommonRequest;

class MessageProduce
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
     * @return MessageProduce
     */
    public function setCrc(Int32 $crc): MessageProduce
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
     * @return MessageProduce
     */
    public function setMagicByte(Int8 $magicByte): MessageProduce
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
     * @return MessageProduce
     */
    public function setAttributes(Int8 $attributes): MessageProduce
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
     * @return MessageProduce
     */
    public function setKey(Bytes32 $key): MessageProduce
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
     * @return MessageProduce
     */
    public function setValue(Bytes32 $value): MessageProduce
    {
        $this->value = $value;

        $this->autoRecode();

        return $this;
    }


    private function autoRecode()
    {
        $this->setMagicByte(Int8::value(0));
        $this->setAttributes(Int8::value(0));
        $this->setKey(Bytes32::value(''));

        $protocol = new CommonRequest();
        $fn = function ($instance, $className, $wrapperProtocol, $propertyName) {
            if ($instance instanceof self && $propertyName == 'crc') {
                return true;
            } else {
                return false;
            }
        };
        $protocol->setContinueCallBack($fn);
        $data = $protocol->packProtocol(static::class, $this);

        $this->setCrc(Int32::value((string)crc32($data)));
    }
}
