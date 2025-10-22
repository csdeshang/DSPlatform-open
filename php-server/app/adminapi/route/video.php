<?php

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 视频管理
Route::group('video', function () {
    // 视频分类管理
    Route::get('category/tree', 'video.VideoCategory/getVideoCategoryTree');
    Route::get('category/:id', 'video.VideoCategory/getVideoCategoryInfo');
    Route::post('category', 'video.VideoCategory/createVideoCategory');
    Route::put('category/:id', 'video.VideoCategory/updateVideoCategory');
    Route::delete('category/:id', 'video.VideoCategory/deleteVideoCategory');
    Route::post('category/toggle-field', 'video.VideoCategory/toggleVideoCategoryField');
    
    // 短视频分页列表
    Route::get('short/pages', 'video.VideoShort/getVideoShortPages');
    
    // 短视频详情
    Route::get('short/:id', 'video.VideoShort/getVideoShortInfo');
    
    // 短视频信息更新
    Route::put('short/:id', 'video.VideoShort/updateVideoShort');
    
    // 短视频字段状态切换
    Route::post('short/toggle-field', 'video.VideoShort/toggleVideoShortField');
    
    // 短视频审核
    Route::post('short/audit', 'video.VideoShort/auditVideoShort');
    
    // 视频评论管理
    Route::get('comment/pages', 'video.VideoComment/getVideoCommentPages');
    Route::post('comment/toggle-field', 'video.VideoComment/toggleVideoCommentField');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]); 