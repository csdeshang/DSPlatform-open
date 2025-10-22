<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('tbl-store', function () {

    // 公共店铺信息
    Route::get('store/pages', 'tblStore.TblStore/getTblStorePage');
    Route::get('store/list', 'tblStore.TblStore/getTblStoreList');
    Route::post('store/audit', 'tblStore.TblStore/auditTblStore');
    // PS：此路由需要放在最后 否则会执行
    Route::get('store/:id', 'tblStore.TblStore/getTblStoreInfo');
    Route::post('store', 'tblStore.TblStore/createTblStore');
    Route::put('store/:id', 'tblStore.TblStore/updateTblStore');
    Route::delete('store/:id', 'tblStore.TblStore/deleteTblStore');
    



    // 多平台通用 公共店铺分类信息
    Route::get('category/tree', 'tblStore.TblStoreCategory/getTblStoreCategoryTree');
    Route::get('category/:id', 'tblStore.TblStoreCategory/getTblStoreCategoryInfo');
    Route::post('category', 'tblStore.TblStoreCategory/createTblStoreCategory');
    Route::put('category/:id', 'tblStore.TblStoreCategory/updateTblStoreCategory');
    Route::delete('category/:id', 'tblStore.TblStoreCategory/deleteTblStoreCategory');





    // 店铺授权用户
    // 获取店铺授权用户列表
    Route::get('auth/users', 'tblStore.TblStoreAuthUser/getTblStoreAuthUserList');
    // 添加店铺授权用户
    Route::post('auth/user/create', 'tblStore.TblStoreAuthUser/createTblStoreAuthUser');
    // 删除店铺授权用户
    Route::post('auth/user/delete', 'tblStore.TblStoreAuthUser/deleteTblStoreAuthUser');

    // 店铺打印机管理
    // 获取店铺打印机列表
    Route::get('printer/pages', 'tblStore.TblStorePrinter/getTblStorePrinterPages');
    // 获取店铺打印机详情
    Route::get('printer/info/:id', 'tblStore.TblStorePrinter/getTblStorePrinterInfo');
    // 获取店铺打印机日志列表
    Route::get('printer/log/pages', 'tblStore.TblStorePrinter/getTblStorePrinterLogPages');

    // ----------------






})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
