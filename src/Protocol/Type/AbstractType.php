<?php
declare(strict_types=1);

namespace Kafka\Protocol\Type;

use Kafka\Exceptions\ProtocolTypeException;

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

    abstract function setValue($value);

    abstract function getValue();
}