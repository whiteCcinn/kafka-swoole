<?php
declare(strict_types=1);

namespace Kafka\Event;

use Symfony\Contracts\EventDispatcher\Event;

class SinkerEvent extends Event
{
    public const NAME = 'sinker';
}