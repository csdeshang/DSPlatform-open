<?php

namespace app;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\NotFoundException;
use app\deshang\exceptions\AuthException;
use app\deshang\exceptions\PayException;
use app\deshang\exceptions\SystemException;
use app\deshang\exceptions\PermissionException;
use app\deshang\exceptions\StateException;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

use app\adminapi\service\system\SysErrorLogsService;

/**
 * 应用异常处理类
 */

class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 不需要记录到数据库的异常类列表
     * @var array
     */
    protected $ignoreDatabase = [
        // ValidateException::class,  // 验证异常只记录到日志，不记录到数据库
        // HttpException::class,        // HTTP异常只记录到日志，不记录到数据库
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        //-------------- 系统错误日志（数据库记录）------------------
        // 检查是否需要记录到数据库
        if (!$this->isIgnoreDatabase($exception)) {
            // 检查错误日志开关配置
            if (sysConfig('system:error_log_enabled') != '1') {
                // 如果错误日志开关关闭，只记录到日志文件，不记录到数据库
                parent::report($exception);
                return;
            }

            $request = request();

            // 获取异常类名
            $class = get_class($exception);
            $parts = explode('\\', $class);
            $exception_class = end($parts);

            // 处理 previous 异常信息（避免重复调用 getPrevious()）
            $previousInfo = '';
            $previous = $exception->getPrevious();
            if ($previous !== null) {
                $previousData = [
                    'class' => get_class($previous),
                    'message' => $previous->getMessage(),
                    'code' => $previous->getCode(),
                    'file' => $previous->getFile(),
                    'line' => $previous->getLine(),
                ];
                $previousInfo = json_encode($previousData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }

            $log = array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                // 'code' => $this->getCode($exception),
                'code' => $exception->getCode(),
                'exception_class' => $exception_class,
                'ip' => $request->ip(),
                'method' => $request->method(),
                'root' => str_replace("/", "", $request->rootUrl()),
                'controller' => $request->controller(),
                'action' => $request->action(),
                'url' => $request->url(),
                'params' => json_encode($request->param()),
                'duration' => ceil(microtime(true) * 1000 - ($request->time(true) * 1000)),
                'previous' => $previousInfo,
            );

            (new SysErrorLogsService())->createSysErrorLogs($log);
        }

        //-------------- 日志文件记录 ------------------
        // 调用父类方法，由父类根据 $ignoreReport 决定是否记录到日志文件
        parent::report($exception);
    }

    /**
     * 检查异常是否在数据库忽略列表中
     *
     * @access protected
     * @param  Throwable $exception
     * @return bool
     */
    protected function isIgnoreDatabase(Throwable $exception): bool
    {
        foreach ($this->ignoreDatabase as $class) {
            if ($exception instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        if ($e instanceof ValidateException) {
            return ds_json_error($e->getMessage());
        } elseif ($e instanceof CommonException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        } elseif ($e instanceof AuthException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        } elseif ($e instanceof PayException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        } elseif ($e instanceof NotFoundException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        } elseif ($e instanceof SystemException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        } elseif ($e instanceof PermissionException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        } elseif ($e instanceof StateException) {
            return ds_json_error($e->getMessage(), '', 10001, $e->getCode());
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
