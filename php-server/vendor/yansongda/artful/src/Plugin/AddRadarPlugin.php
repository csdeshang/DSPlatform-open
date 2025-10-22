<?php

declare(strict_types=1);

namespace Yansongda\Artful\Plugin;

use Closure;
use GuzzleHttp\Psr7\Request;
use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;

use function Yansongda\Artful\get_radar_body;
use function Yansongda\Artful\get_radar_headers;
use function Yansongda\Artful\get_radar_method;
use function Yansongda\Artful\get_radar_url;

class AddRadarPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[AddRadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $payload = $rocket->getPayload();

        $rocket->setRadar(new Request(
            get_radar_method($payload) ?? 'POST',
            get_radar_url($payload),
            get_radar_headers($payload) ?? $this->getHeaders(),
            get_radar_body($payload),
        ));

        Logger::info('[AddRadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'yansongda/artful-v1',
            'Content-Type' => 'application/json;charset=utf-8',
        ];
    }
}
