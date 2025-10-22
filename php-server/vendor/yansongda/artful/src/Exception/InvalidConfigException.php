<?php

declare(strict_types=1);

namespace Yansongda\Artful\Exception;

use Throwable;

/**
 * @codeCoverageIgnore
 */
class InvalidConfigException extends Exception
{
    public function __construct(int $code = self::CONFIG_ERROR, string $message = '配置异常', mixed $extra = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $extra, $previous);
    }
}
