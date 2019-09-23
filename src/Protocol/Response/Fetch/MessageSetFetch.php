<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Fetch;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;

class MessageSetFetch
{
    use ToArrayTrait;

    /**
     * @var Int64 $offset
     */
    private $offset;

    /**
     * @var Int32 $messageSetSize
     */
    private $messageSetSize;

    /**
     * @var MessageFetch $message
     */
    private $message;

    /**
     * @return Int64
     */
    public function getOffset(): Int64
    {
        return $this->offset;
    }

    /**
     * @param Int64 $offset
     *
     * @return MessageSetFetch
     */
    public function setOffset(Int64 $offset): MessageSetFetch
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getMessageSetSize(): Int32
    {
        return $this->messageSetSize;
    }

    /**
     * @param Int32 $messageSetSize
     *
     * @return MessageSetFetch
     */
    public function setMessageSetSize(Int32 $messageSetSize): MessageSetFetch
    {
        $this->messageSetSize = $messageSetSize;

        return $this;
    }

    /**
     * @return MessageFetch
     */
    public function getMessage(): MessageFetch
    {
        return $this->message;
    }

    /**
     * @param MessageFetch $message
     *
     * @return MessageSetFetch
     */
    public function setMessage(MessageFetch $message): MessageSetFetch
    {
        $this->message = $message;

        return $this;
    }
}
