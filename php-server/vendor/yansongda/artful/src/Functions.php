<?php

declare(strict_types=1);

namespace Yansongda\Artful;

use Closure;
use Yansongda\Artful\Contract\DirectionInterface;
use Yansongda\Artful\Contract\PackerInterface;
use Yansongda\Artful\Direction\NoHttpRequestDirection;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Artful\Exception\Exception;
use Yansongda\Artful\Exception\InvalidParamsException;
use Yansongda\Artful\Exception\ServiceNotFoundException;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

function should_do_http_request(string $direction): bool
{
    return NoHttpRequestDirection::class !== $direction
        && !in_array(NoHttpRequestDirection::class, class_parents($direction));
}

/**
 * @throws InvalidParamsException
 */
function get_direction(mixed $direction): DirectionInterface
{
    try {
        $direction = Artful::get($direction);

        $direction = is_string($direction) ? Artful::get($direction) : $direction;
    } catch (ContainerException|ServiceNotFoundException) {
    }

    if (!$direction instanceof DirectionInterface) {
        throw new InvalidParamsException(Exception::PARAMS_DIRECTION_INVALID, '参数异常: 配置的 `DirectionInterface` 未实现 `DirectionInterface`');
    }

    return $direction;
}

/**
 * @throws InvalidParamsException
 */
function get_packer(mixed $packer): PackerInterface
{
    try {
        $packer = Artful::get($packer);

        $packer = is_string($packer) ? Artful::get($packer) : $packer;
    } catch (ContainerException|ServiceNotFoundException) {
    }

    if (!$packer instanceof PackerInterface) {
        throw new InvalidParamsException(Exception::PARAMS_PACKER_INVALID, '参数异常: 配置的 `PackerInterface` 未实现 `PackerInterface`');
    }

    return $packer;
}

function filter_params(null|array|Collection $params, ?Closure $closure = null): Collection
{
    $params = Collection::wrap($params);

    return $params->filter(static fn ($v, $k) => !Str::startsWith($k, '_') && !is_null($v) && (empty($closure) || $closure($k, $v)));
}

function get_radar_method(?Collection $payload): ?string
{
    $string = $payload?->get('_method') ?? null;

    if (is_null($string)) {
        return null;
    }

    return strtoupper($string);
}

function get_radar_url(?Collection $payload): ?string
{
    return $payload?->get('_url') ?? null;
}

function get_radar_body(?Collection $payload): mixed
{
    return $payload?->get('_body') ?? null;
}

function get_radar_headers(?Collection $payload): mixed
{
    return $payload?->get('_headers') ?? null;
}
