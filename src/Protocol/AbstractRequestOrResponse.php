<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Protocol\TraitStructure\ValueTrait;
use Kafka\Protocol\Type\Int32;
use Kafka\Support\Str;

/**
 * Class AbstractRequestOrResponse
 *
 * @property $size
 *
 * @package Kafka\Protocol
 */
abstract class AbstractRequestOrResponse
{
//    use ValueTrait;

    /**
     * @var Int32 $size
     */
    protected $size;

    /**
     * @return Int32
     */
    public function getSize(): Int32
    {
        return $this->size;
    }

    /**
     * @param Int32 $size
     *
     * @return AbstractRequestOrResponse
     */
    public function setSize(Int32 $size): AbstractRequestOrResponse
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $getMethod = Str::camel('get_' . $name);
        $value = $this->{$getMethod}();

        return $value;
    }
}
