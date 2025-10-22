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
    Route::get('refresh_token', 'login.Login/refreshToken');
    //用户退出
    Route::get('logout', 'login.Login/logout');

    

    //生成验证码
    Route::get('captcha/create', 'login.Captcha/create');

    //一次校验验证码
    Route::get('captcha/check', 'login.Captcha/check');
});


// TEST 测试
Route::group('test', function () {
    //测试

    Route::get('sms', 'test/sms');
});




// 加载其他平台路由[后台]
PlatformLoader::loadRoute('adminapi');