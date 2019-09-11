<?php
declare(strict_types=1);

namespace Kafka\Protocol\Type;

use Kafka\Exception\ProtocolTypeException;

/**
 * Class AbstractType
 *
 * @package Kafka\Protocols\Type
 */
abstract class AbstractType
{
    /** @var string $wrapperProtocol */
    protected static $wrapperProtocol = '';

    /**
     * @return string
     * @throws ProtocolTypeException
     */
    public static function getWrapperProtocol(): string
    {
        if (static::$wrapperProtocol === '') {
            throw new ProtocolTypeException("protocol not be allow empty");
        }

        return static::$wrapperProtocol;
    }

    /**
     * @param $value
     *
     * @return AbstractType | Arrays32 | Bytes32 | Int8 | Int16 | Int32 | Int64 | String16
     */
    public static function value($value)
    {
        $typeObject = new static();
        $typeObject->setValue($value);

        return $typeObject;
    }

    abstract function setValue($value);

    abstract function getValue();
}