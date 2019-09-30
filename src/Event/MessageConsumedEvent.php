<?php
declare(strict_types=1);

namespace Kafka\Event;

use Symfony\Contracts\EventDispatcher\Event;

class MessageConsumedEvent extends Event
{
    public const NAME = 'message.consumed';

    /**
     * @var int $type
     */
    private $type;

    /**
     * @var string $topic
     */
    private $topic;

    /**
     * @var int $partition
     */
    private $partition;

    /**
     * @var int $offset
     */
    private $offset;

    /**
     * MessageConsumedEvent constructor.
     *
     * @param int    $type
     * @param string $topic
     * @param int    $partition
     * @param int    $offset
     */
    public function __construct(int $type, string $topic, int $partition, int $offset)
    {
        $this->type = $type;
        $this->topic = $topic;
        $this->partition = $partition;
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return int
     */
    public function getPartition(): int
    {
        return $this->partition;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}