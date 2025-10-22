<?php

namespace app\adminapi\middleware;

use app\Request;
use app\deshang\utils\JwtToken;

/**
 * admin用户登录token验证
 */
class AdminAuthorizeToken
{

    public function handle(Request $request, \Closure $next)
    {
        
        //通过配置来设置系统header参数
        $token = $request->header('access-token');

        if (!$token) {
            return ds_json_error('您未有权限访问',[],10001,403);
        }

        // echo $token;exit;
        $token_info = (new JwtToken())->validateToken($token);

        if ($token_info == false) {
            return ds_json_error('后台登录状态已失效，请重新登录',[],10001,401);
        }
        

        if ($token_info['role'] != 'admin'){
            return ds_json_error('后台身份验证失败，请重新登录',[],10001,403);
        }

        $request->user_id = $token_info['user_id']?? 0;
        $request->username = $token_info['username']?? '';
        $request->admin_is_super = $token_info['admin_is_super']?? 0;


        return $next($request);
    }
}
