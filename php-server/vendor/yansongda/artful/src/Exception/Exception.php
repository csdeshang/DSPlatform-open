<?php

declare(strict_types=1);

namespace Yansongda\Artful\Exception;

use Throwable;

class Exception extends \Exception
{
    public const UNKNOWN_ERROR = 9999;

    /**
     * 关于容器.
     */
    public const CONTAINER_ERROR = 9100;

    public const CONTAINER_NOT_FOUND = 9101;

    public const CONTAINER_SERVICE_NOT_FOUND = 9102;

    /**
     * 关于参数.
     */
    public const PARAMS_ERROR = 9200;

    public const PARAMS_DIRECTION_INVALID = 9201;

    public const PARAMS_PACKER_INVALID = 9202;

    public const PARAMS_EVENT_DRIVER_INVALID = 9203;

    public const PARAMS_HTTP_CLIENT_INVALID = 9204;

    public const PARAMS_LOGGER_DRIVER_INVALID = 9205;

    public const PARAMS_HTTP_CLIENT_FACTORY_INVALID = 9206;

    public const PARAMS_PLUGIN_INCOMPATIBLE = 9207;

    public const PARAMS_PARSER_DIRECTION_INVALID = 9208;

    public const PARAMS_SHORTCUT_INVALID = 9209;

    /**
     * 关于响应.
     */
    public const RESPONSE_ERROR = 9300;

    public const REQUEST_RESPONSE_ERROR = 9301;

    public const RESPONSE_UNPACK_ERROR = 9302;

    public const RESPONSE_EMPTY = 9303;

    /**
     * 关于配置.
     */
    public const CONFIG_ERROR = 9400;

    public mixed $extra;

    public function __construct(string $message = '未知异常', int $code = self::UNKNOWN_ERROR, mixed $extra = null, ?Throwable $previous = null)
    {
        $this->extra = $extra;

        parent::__construct($message, $code, $previous);
    }
}
