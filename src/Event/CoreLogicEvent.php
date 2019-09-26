<?php
declare(strict_types=1);

namespace Kafka\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CoreLogicEvent extends Event
{
    public const NAME = 'corelogic';
}