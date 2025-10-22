<?php

declare(strict_types=1);

namespace Yansongda\Artful;

use Yansongda\Artful\Contract\ConfigInterface;
use Yansongda\Artful\Contract\LoggerInterface;
use Yansongda\Artful\Exception\ContainerException;
use Yansongda\Artful\Exception\Exception;
use Yansongda\Artful\Exception\InvalidParamsException;
use Yansongda\Artful\Exception\ServiceNotFoundException;

/**
 * @method static void emergency($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void log($message, array $context = [])
 */
class Logger
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidParamsException
     */
    public static function __callStatic(string $method, array $args): void
    {
        if (!Artful::hasContainer() || !Artful::has(LoggerInterface::class)
            || false === Artful::get(ConfigInterface::class)->get('logger.enable', false)) {
            return;
        }

        $class = Artful::get(LoggerInterface::class);

        if ($class instanceof \Psr\Log\LoggerInterface) {
            $class->{$method}(...$args);

            return;
        }

        throw new InvalidParamsException(Exception::PARAMS_LOGGER_DRIVER_INVALID, '配置异常: 配置的 `LoggerInterface` 不符合 PSR 规范');
    }
}
