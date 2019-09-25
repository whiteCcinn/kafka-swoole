<?php
declare(strict_types=1);

namespace Kafka\Protocol\Type;

class Int16S extends AbstractType
{
    protected static $wrapperProtocol = 's';

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
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}