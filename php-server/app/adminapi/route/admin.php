<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('admin', function () {

    // 当前管理员信息
    // 当前管理员 获取加载路由信息
    Route::get('current/info', 'admin.CurrentAdmin/getCurrentAdminInfo');
    Route::get('current/menus', 'admin.CurrentAdmin/getCurrentAdminMenus');
    // 当前管理员 修改密码
    Route::put('current/password', 'admin.CurrentAdmin/editCurrentAdminPassword');


    // 管理员管理
    // admins/pages (2段) 必须在 admins/:id (2段) 前面 否则 GET /admins/pages 会被 admins/:id 匹配
    Route::get('admins/pages', 'admin.Admin/getAdminPages');
    Route::get('admins/:id', 'admin.Admin/getAdminInfo');
    Route::post('admins', 'admin.Admin/createAdmin');
    Route::put('admins/:id', 'admin.Admin/updateAdmin');
    Route::delete('admins/:id', 'admin.Admin/deleteAdmin');


    // 管理员日志
    Route::get('logs/pages', 'admin.AdminLogs/getAdminLogsPages');
    Route::get('logs/:id', 'admin.AdminLogs/getAdminLogsInfo');

    
    //角色
    Route::get('roles/list', 'admin.AdminRole/getAdminRoleList');
    Route::get('roles/:id', 'admin.AdminRole/getAdminRoleInfo');
    Route::post('roles', 'admin.AdminRole/createAdminRole');

    // 更新角色权限 [PS: roles/:id/rules  优先匹配  以免指向路径错误]
    Route::put('roles/:id/rules', 'admin.AdminRole/updateAdminRoleRules');
    Route::put('roles/:id', 'admin.AdminRole/updateAdminRole');
    // 单个删除
    Route::delete('roles/:id', 'admin.AdminRole/deleteAdminRole');
    // 批量删除
    // Route::post('roles/del-batch', 'admin.AdminRole/deleteBatchAdminRole');



    //权限
    Route::get('menus/tree', 'admin.AdminMenu/getAdminMenuTree');
    Route::get('menus/options', 'admin.AdminMenu/getAdminMenuOptions');
    Route::get('menus/:id', 'admin.AdminMenu/getAdminMenuInfo');
    Route::post('menus', 'admin.AdminMenu/createAdminMenu');
    Route::put('menus/:id', 'admin.AdminMenu/updateAdminMenu');
    Route::delete('menus/:id', 'admin.AdminMenu/deleteAdminMenu');

    

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);