<?php
declare(strict_types=1);

namespace Kafka\Event;

use Symfony\Contracts\EventDispatcher\Event;

class SinkerOtherEvent extends Event
{
    public const NAME = 'sinker.other';
}