<?php

declare(strict_types=1);

namespace Yansongda\Artful\Exception;

use Throwable;

class InvalidParamsException extends Exception
{
    public function __construct(int $code = self::PARAMS_ERROR, string $message = '参数异常', mixed $extra = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $extra, $previous);
    }
}
