<?php
declare(strict_types=1);

namespace Kafka\Protocol\Type;

class Int8 extends AbstractType
{
    /** @var string $wrapperProtocol */
    protected static $wrapperProtocol = 'C';

    /** @var mixed $value */
    protected $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}