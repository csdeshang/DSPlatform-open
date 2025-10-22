<?php

namespace AlibabaCloud\Dara\Util;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * This is a console module.
 */
class Console
{
    /**
     * @var Logger
     */
    private static $loggerDriver;

    /**
     * Console val with log level into stdout.
     *
     * @param string $val the printing string
     *
     * @throws \Exception
     *
     * @example \[LOG\] tea console example
     */
    public static function log($val)
    {
        self::logger()->log(200, $val);
    }

    /**
     * Console val with info level into stdout.
     *
     * @param string $val the printing string
     *
     * @throws \Exception
     *
     * @example \[INFO\] tea console example
     */
    public static function info($val)
    {
        self::logger()->info($val);
    }

    /**
     * Console val with warning level into stdout.
     *
     * @param string $val the printing string
     *
     * @throws \Exception
     *
     * @example \[WARNING\] tea console example
     */
    public static function warning($val)
    {
        self::logger()->warning($val);
    }

    /**
     * Console val with debug level into stdout.
     *
     * @param string $val the printing string
     *
     * @throws \Exception
     *
     * @example \[DEBUG\] tea console example
     */
    public static function debug($val)
    {
        self::logger()->debug($val);
    }

    /**
     * Console val with error level into stderr.
     *
     * @param string $val the printing string
     *
     * @throws \Exception
     *
     * @example \[ERROR\] tea console example
     */
    public static function error($val)
    {
        self::logger()->error($val);
    }

    /**
     * @param AbstractProcessingHandler $handler
     */
    public static function pushHandler($handler)
    {
        self::$loggerDriver->pushHandler($handler);
    }

    /**
     * @return Logger
     */
    public static function logger()
    {
        if (null === self::$loggerDriver) {
            self::$loggerDriver = new Logger('tea-console-log');
            self::$loggerDriver->pushHandler(new StreamHandler('php://stderr', 0));
        }

        return self::$loggerDriver;
    }
}