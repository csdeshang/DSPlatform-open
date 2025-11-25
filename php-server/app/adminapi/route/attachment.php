<?php

/**
 * 管理员授权
 */

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('attachment', function () {

    
    //附件分类
    Route::get('categories', 'attachment.AttachmentCate/getAttachmentCateList');
    Route::post('categories', 'attachment.AttachmentCate/createAttachmentCate');
    Route::put('categories/:id', 'attachment.AttachmentCate/updateAttachmentCate');
    Route::delete('categories/:id', 'attachment.AttachmentCate/deleteAttachmentCate');


    //附件文件管理
    // 注意路由顺序：批量操作（2段）和特殊路径（2段）必须在动态路径（2段）前面
    Route::get('files/pages', 'attachment.AttachmentFile/getAttachmentFilePages');
    // 上传图片
    Route::post('files/image', 'attachment.AttachmentFile/image');
    // 上传视频
    Route::post('files/video', 'attachment.AttachmentFile/video');
    // 批量更新附件文件（重命名、移动分类）
    Route::post('files/batch', 'attachment.AttachmentFile/updateBatchAttachmentFile');
    // 批量删除附件文件
    Route::delete('files/batch', 'attachment.AttachmentFile/deleteBatchAttachmentFile');
    


})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
