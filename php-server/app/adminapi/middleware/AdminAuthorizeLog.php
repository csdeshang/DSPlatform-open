<?php

namespace app\adminapi\middleware;

use app\Request;


/**
 * admin日志中间件
 */
class AdminAuthorizeLog
{
    public function handle(Request $request, \Closure $next)
    {

        return $next($request);
    }
}
