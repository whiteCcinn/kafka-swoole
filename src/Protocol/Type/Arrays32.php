<?php
declare(strict_types=1);

namespace Kafka\Protocol\Type;

class Arrays32 extends AbstractType
{
    /**
     * @var string
     */
    protected static $wrapperProtocol = 'N';

    /**
     * @var $value
     */
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