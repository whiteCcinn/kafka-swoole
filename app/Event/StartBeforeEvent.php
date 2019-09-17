<?php
declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StartBeforeEvent extends Event
{
    public const NAME = 'start.before';
}