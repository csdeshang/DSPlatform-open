<?php

namespace app\common\middleware;

use app\Request;
use Closure;

/**
 * http跨域请求中间件
 * Class AllowCrossDomain
 * @package app\common\middleware
 */
class AllowCrossMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        // manage-store-id access-token   refresh-token

        header("Access-Control-Allow-Headers: Authorization, Sec-Fetch-Mode, DNT, X-Mx-ReqToken, Keep-Alive, User-Agent, If-Match, If-None-Match, If-Unmodified-Since, X-Requested-With, If-Modified-Since, Cache-Control, Content-Type, Accept-Language, Origin, Accept-Encoding,Access-Token, manage-store-id, access-token, refresh-token");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Max-Age: 1728000');
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Origin: *');
        return $next($request);
    }
}
