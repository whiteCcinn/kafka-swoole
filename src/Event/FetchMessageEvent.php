<?php
declare(strict_types=1);

namespace Kafka\Event;

use Symfony\Contracts\EventDispatcher\Event;

class FetchMessageEvent extends Event
{
    public const NAME = 'fetch.message';

    /**
     * @var int $offset
     */
    private $offset;

    /**
     * @var string $message
     */
    private $message;

    /**
     * FetchMessageEvent constructor.
     *
     * @param int    $offset
     * @param string $message
     */
    public function __construct(int $offset, string $message)
    {
        $this->offset = $offset;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}