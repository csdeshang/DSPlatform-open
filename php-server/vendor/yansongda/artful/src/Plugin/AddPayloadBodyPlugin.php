<?php

declare(strict_types=1);

namespace Yansongda\Artful\Plugin;

use Closure;
use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Exception\InvalidParamsException;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;

use function Yansongda\Artful\filter_params;
use function Yansongda\Artful\get_packer;

class AddPayloadBodyPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[AddPayloadBodyPlugin] 插件开始装载', ['rocket' => $rocket]);

        $packer = get_packer($rocket->getPacker());

        $rocket->mergePayload(['_body' => $packer->pack(filter_params($rocket->getPayload()))]);

        Logger::info('[AddPayloadBodyPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
