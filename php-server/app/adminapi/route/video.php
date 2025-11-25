<?php

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 视频管理
Route::group('video', function () {
    // 视频分类管理
    // categories/tree (2段) 必须在 categories/:id (2段) 前面 否则 GET /categories/tree 会被 categories/:id 匹配
    Route::get('categories/tree', 'video.VideoCategory/getVideoCategoryTree');
    Route::post('categories', 'video.VideoCategory/createVideoCategory');
    // categories/:id/toggle-field (3段) 必须在 categories/:id (2段) 前面
    Route::patch('categories/:id/toggle-field', 'video.VideoCategory/toggleVideoCategoryField');
    Route::get('categories/:id', 'video.VideoCategory/getVideoCategoryInfo');
    Route::put('categories/:id', 'video.VideoCategory/updateVideoCategory');
    Route::delete('categories/:id', 'video.VideoCategory/deleteVideoCategory');
    

    
    // 短视频管理
    // shorts/pages (2段) 必须在 shorts/:id (2段) 前面 否则 GET /shorts/pages 会被 shorts/:id 匹配
    Route::get('shorts/pages', 'video.VideoShort/getVideoShortPages');
    // shorts/:id/toggle-field, shorts/:id/audit (3段) 必须在 shorts/:id (2段) 前面
    Route::patch('shorts/:id/toggle-field', 'video.VideoShort/toggleVideoShortField');
    Route::patch('shorts/:id/audit', 'video.VideoShort/auditVideoShort');
    Route::get('shorts/:id', 'video.VideoShort/getVideoShortInfo');
    Route::put('shorts/:id', 'video.VideoShort/updateVideoShort');
    


    // 视频评论管理
    // comments/pages (2段) 必须在 comments/:id (2段) 前面 否则 GET /comments/pages 会被 comments/:id 匹配
    Route::get('comments/pages', 'video.VideoComment/getVideoCommentPages');
    // comments/:id/toggle-field (3段) 必须在 comments/:id (2段) 前面
    Route::patch('comments/:id/toggle-field', 'video.VideoComment/toggleVideoCommentField');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]); 