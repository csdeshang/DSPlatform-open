<?php

declare(strict_types=1);

namespace Yansongda\Artful\Event;

class ArtfulStart extends Event
{
    public function __construct(public array $plugins, public array $params) {}
}
