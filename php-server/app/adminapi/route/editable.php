<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;





// 可编辑页面
Route::group('editable', function () {




    

    // 可编辑页面列表
    Route::get('editable/pages', 'editable.EditablePage/getEditablePages');
    // 创建可编辑页面
    Route::post('editable', 'editable.EditablePage/createEditablePage');
    // 获取可编辑页面信息
    Route::get('editable/:id', 'editable.EditablePage/getEditablePageInfo');
    // 更新可编辑页面
    Route::put('editable/:id', 'editable.EditablePage/updateEditablePage');
    // 删除可编辑页面
    Route::delete('editable/:id', 'editable.EditablePage/deleteEditablePage');






})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);