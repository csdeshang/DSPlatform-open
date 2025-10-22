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
    Route::get('category/list', 'attachment.AttachmentCate/getAttachmentCateList');
    Route::post('category', 'attachment.AttachmentCate/createAttachmentCate');
    Route::put('category/:id', 'attachment.AttachmentCate/updateAttachmentCate');
    Route::delete('category/:id', 'attachment.AttachmentCate/deleteAttachmentCate');


    // 添加视频
    Route::post('file/video', 'attachment.AttachmentFile/video');


    //添加图片
    Route::post('file/image', 'attachment.AttachmentFile/image');
    Route::get('file/pages', 'attachment.AttachmentFile/getAttachmentFilePages');
    // 批量编辑图片
    Route::post('file/update-batch', 'attachment.AttachmentFile/updateBatchAttachmentFile');
    // 批量删除图片
    Route::post('file/del-batch', 'attachment.AttachmentFile/deleteBatchAttachmentFile');
    


})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
