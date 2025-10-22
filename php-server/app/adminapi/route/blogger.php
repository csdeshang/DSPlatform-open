<?php

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 博主管理
Route::group('blogger', function () {
    // 博主分页列表
    Route::get('blogger/pages', 'blogger.Blogger/getBloggerPages');
    
    // 博主详情
    Route::get('blogger/:id', 'blogger.Blogger/getBloggerInfo');
    
    // 博主信息更新
    Route::put('blogger/:id', 'blogger.Blogger/updateBlogger');
    
    // 博主字段状态切换
    Route::post('blogger/toggle-field', 'blogger.Blogger/toggleBloggerField');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
