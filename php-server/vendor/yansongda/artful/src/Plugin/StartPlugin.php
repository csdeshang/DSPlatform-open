<?php

declare(strict_types=1);

namespace Yansongda\Artful\Plugin;

use Closure;
use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;

class StartPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[StartPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($rocket->getParams());

        Logger::info('[StartPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
