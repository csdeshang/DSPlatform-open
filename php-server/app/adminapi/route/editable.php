<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;





// 可编辑页面
Route::group('editable', function () {




    

    // 可编辑页面列表
    // editables/pages (2段) 必须在 editables/:id (2段) 前面
    Route::get('editables/pages', 'editable.EditablePage/getEditablePages');
    // 创建可编辑页面
    Route::post('editables', 'editable.EditablePage/createEditablePage');
    // 获取可编辑页面信息
    Route::get('editables/:id', 'editable.EditablePage/getEditablePageInfo');
    // 更新可编辑页面
    Route::put('editables/:id', 'editable.EditablePage/updateEditablePage');
    // 删除可编辑页面
    Route::delete('editables/:id', 'editable.EditablePage/deleteEditablePage');






})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);