<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('distributor', function () {

    // 分销商申请
    // applies/pages (2段) 必须在 applies/:id (2段) 前面 否则 GET /applies/pages 会被 applies/:id 匹配
    Route::get('applies/pages', 'distributor.DistributorApply/getDistributorApplyPages');
    // applies/:id/audit (3段) 必须在 applies/:id (2段) 前面（如果将来添加详情路由）
    Route::patch('applies/:id/audit', 'distributor.DistributorApply/auditDistributorApply');

    // 分销商等级
    // levels/list (2段) 必须在 levels/:id (2段) 前面 否则 GET /levels/list 会被 levels/:id 匹配
    Route::get('levels/list', 'distributor.DistributorLevel/getDistributorLevelList');
    Route::get('levels/:id', 'distributor.DistributorLevel/getDistributorLevelInfo');
    Route::post('levels', 'distributor.DistributorLevel/createDistributorLevel');
    Route::put('levels/:id', 'distributor.DistributorLevel/updateDistributorLevel');
    Route::delete('levels/:id', 'distributor.DistributorLevel/deleteDistributorLevel');

    // 分销商订单
    Route::get('orders/pages', 'distributor.DistributorOrder/getDistributorOrderPages');
    Route::get('orders/list', 'distributor.DistributorOrder/getDistributorOrderList');
    // 分销商余额流水
    Route::get('balance-logs/pages', 'distributor.DistributorBalance/getDistributorBalanceLogPages');
    
    // distributors/:id/balance (3段) 必须在 distributors/:id (2段) 前面 否则 PUT /distributors/123/balance 会被 distributors/:id 匹配（如果将来添加 PUT distributors/:id）
    Route::put('distributors/:id/balance', 'distributor.DistributorBalance/modifyDistributorBalance');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);