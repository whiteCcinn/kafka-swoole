<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request;

use Kafka\Protocol\AbstractRequest;

use Kafka\Protocol\Type\String16;

class FindCoordinatorRequest extends AbstractRequest
{
    /** @var String16 $key */
    private $key;

    /**
     * @return String16
     */
    public function getKey(): String16
    {
        return $this->key;
    }

    /**
     * @param String16 $key
     *
     * @return FindCoordinatorRequest
     */
    public function setKey(String16 $key): FindCoordinatorRequest
    {
        $this->key = $key;

        return $this;
    }
}
