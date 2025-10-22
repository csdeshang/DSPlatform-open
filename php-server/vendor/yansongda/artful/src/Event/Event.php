<?php

declare(strict_types=1);

namespace Yansongda\Artful\Event;

use Yansongda\Artful\Rocket;

class Event
{
    public function __construct(public ?Rocket $rocket = null) {}
}
