<?php

use think\facade\Route;
use app\deshang\core\PlatformLoader;

//Thtinkphp 路由中间件  https://doc.thinkphp.cn/v8_0/route_middleware.html

/**
 * 不需要授权
 */
Route::group(function () {
    //用户登录
    Route::post('login', 'login.Login/login');

    //刷新token
    Route::get('refresh-token', 'login.Login/refreshToken');
    //用户退出
    Route::get('logout', 'login.Login/logout');

    

    //验证码
    // 注意路由顺序：特殊路径（2段）必须在动态路径（2段）前面
    // 校验验证码
    Route::post('captcha/verify', 'login.Captcha/verify');
    // 生成验证码
    Route::post('captcha', 'login.Captcha/create');
});







// 加载其他平台路由[后台]
PlatformLoader::loadRoute('adminapi');