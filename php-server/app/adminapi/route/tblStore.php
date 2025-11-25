<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('tbl-store', function () {

    // 公共店铺信息
    // stores/pages (2段) 必须在 stores/:id (2段) 前面 否则 GET /stores/pages 会被 stores/:id 匹配
    Route::get('stores/pages', 'tblStore.TblStore/getTblStorePage');
    // stores/list (2段) 必须在 stores/:id (2段) 前面 否则 GET /stores/list 会被 stores/:id 匹配
    Route::get('stores/list', 'tblStore.TblStore/getTblStoreList');
    // stores/:id/audit (3段) 必须在 stores/:id (2段) 前面 否则 PATCH /stores/123/audit 会被 stores/:id 匹配
    Route::patch('stores/:id/audit', 'tblStore.TblStore/auditTblStore');
    Route::post('stores', 'tblStore.TblStore/createTblStore');
    Route::get('stores/:id', 'tblStore.TblStore/getTblStoreInfo');
    Route::put('stores/:id', 'tblStore.TblStore/updateTblStore');
    Route::delete('stores/:id', 'tblStore.TblStore/deleteTblStore');
    



    // 多平台通用 公共店铺分类信息
    // categories/tree (2段) 必须在 categories/:id (2段) 前面 否则 GET /categories/tree 会被 categories/:id 匹配
    Route::get('categories/tree', 'tblStore.TblStoreCategory/getTblStoreCategoryTree');
    Route::post('categories', 'tblStore.TblStoreCategory/createTblStoreCategory');
    Route::get('categories/:id', 'tblStore.TblStoreCategory/getTblStoreCategoryInfo');
    Route::put('categories/:id', 'tblStore.TblStoreCategory/updateTblStoreCategory');
    Route::delete('categories/:id', 'tblStore.TblStoreCategory/deleteTblStoreCategory');





    // 店铺授权用户管理
    Route::get('store-auths', 'tblStore.TblStoreAuthUser/getTblStoreAuthUserList');
    Route::post('store-auths', 'tblStore.TblStoreAuthUser/createTblStoreAuthUser');
    // 删除店铺授权用户（使用查询参数：store_id 和 user_id）
    Route::delete('store-auths', 'tblStore.TblStoreAuthUser/deleteTblStoreAuthUser');

    // 店铺打印机管理
    // printers/pages (2段) 必须在 printers/:id (2段) 前面 否则 GET /printers/pages 会被 printers/:id 匹配
    Route::get('printers/pages', 'tblStore.TblStorePrinter/getTblStorePrinterPages');
    // 获取店铺打印机详情
    Route::get('printers/:id', 'tblStore.TblStorePrinter/getTblStorePrinterInfo');
    // 获取店铺打印机日志（独立资源，支持跨打印机查询）
    Route::get('printer-logs/pages', 'tblStore.TblStorePrinter/getTblStorePrinterLogPages');






})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
