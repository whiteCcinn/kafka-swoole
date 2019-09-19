<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\CreateTopics;


use Kafka\Protocol\Type\Int16;
use Kafka\Protocol\Type\String16;

class TopicsCreateTopics
{
    /**
     * The topic name.
     *
     * @var String16 $name
     */
    private $name;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var Int16 $errorCode
     */
    private $errorCode;

    /**
     * @return String16
     */
    public function getName(): String16
    {
        return $this->name;
    }

    /**
     * @param String16 $name
     *
     * @return TopicsCreateTopics
     */
    public function setName(String16 $name): TopicsCreateTopics
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Int16
     */
    public function getErrorCode(): Int16
    {
        return $this->errorCode;
    }

    /**
     * @param Int16 $errorCode
     *
     * @return TopicsCreateTopics
     */
    public function setErrorCode(Int16 $errorCode): TopicsCreateTopics
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}