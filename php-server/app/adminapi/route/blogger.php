<?php

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 博主管理
Route::group('blogger', function () {
    // 博主分页列表
    // bloggers/pages (2段) 必须在 bloggers/:id (2段) 前面 否则 GET /bloggers/pages 会被 bloggers/:id 匹配
    Route::get('bloggers/pages', 'blogger.Blogger/getBloggerPages');
    
    // 博主详情
    Route::get('bloggers/:id', 'blogger.Blogger/getBloggerInfo');
    
    // 博主信息更新
    Route::put('bloggers/:id', 'blogger.Blogger/updateBlogger');
    
    // 博主字段状态切换
    Route::patch('bloggers/:id/toggle-field', 'blogger.Blogger/toggleBloggerField');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
