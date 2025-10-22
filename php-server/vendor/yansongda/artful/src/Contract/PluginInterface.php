<?php

declare(strict_types=1);

namespace Yansongda\Artful\Contract;

use Closure;
use Yansongda\Artful\Rocket;

interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}
