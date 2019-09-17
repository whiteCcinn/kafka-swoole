<?php
declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StartAfterEvent extends Event
{
    public const NAME = 'start.after';
}