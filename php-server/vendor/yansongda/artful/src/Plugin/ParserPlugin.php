<?php

declare(strict_types=1);

namespace Yansongda\Artful\Plugin;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Exception\Exception;
use Yansongda\Artful\Exception\InvalidParamsException;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;

use function Yansongda\Artful\get_direction;
use function Yansongda\Artful\get_packer;

class ParserPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[ParserPlugin] 插件开始装载', ['rocket' => $rocket]);

        $response = $rocket->getDestination();
        $direction = get_direction($rocket->getDirection());
        $packer = get_packer($rocket->getPacker());
        $payload = $rocket->getPayload();

        if (!is_null($response) && !($response instanceof ResponseInterface)) {
            throw new InvalidParamsException(Exception::PARAMS_PARSER_DIRECTION_INVALID, '参数异常: 解析插件中 `Rocket` 的 `destination` 只能是 `null` 或者 `ResponseInterface`');
        }

        $rocket->setDestination($direction->guide($packer, $response, $payload?->all() ?? []));

        Logger::debug('[ParserPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
