<?php
declare(strict_types=1);

namespace Kafka\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CoreLogicAfterEvent extends Event
{
    public const NAME = 'corelogic.after';
}