<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Protocol\Request\Common\RequestHeader;
use Kafka\Protocol\Type\Int32;

abstract class AbstractRequestOrResponse
{
    /** @var Int32 $size */
    private $size;

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
}
