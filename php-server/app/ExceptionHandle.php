<?php

namespace app;

use app\deshang\exceptions\CommonException;
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
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {


        //-------------- 系统错误日志 ------------------
        $request = request();


        $log = array(
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            // 'code' => $this->getCode($exception),
            'code' => $exception->getCode(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'root' => str_replace("/", "", $request->rootUrl()),
            'controller' => $request->controller(),
            'action' => $request->action(),
            'url' => $request->url(),
            'params' => json_encode($request->param()),
            'duration' => ceil(microtime(true) * 1000 - ($request->time(true) * 1000)),
            'previous' => $exception->getPrevious(),
        );



        (new SysErrorLogsService())->createSysErrorLogs($log);



        //--------------------------------

        // 使用内置的方式记录异常日志
        parent::report($exception);
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
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
