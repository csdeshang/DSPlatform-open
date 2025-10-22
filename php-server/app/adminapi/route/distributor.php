<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('distributor', function () {

    Route::get('apply/pages', 'distributor.DistributorApply/getDistributorApplyPages');
    Route::post('apply/audit', 'distributor.DistributorApply/auditDistributorApply');

    // 分销商等级
    Route::get('level/list', 'distributor.DistributorLevel/getDistributorLevelList');
    Route::get('level/:id', 'distributor.DistributorLevel/getDistributorLevelInfo');
    Route::post('level', 'distributor.DistributorLevel/createDistributorLevel');
    Route::put('level/:id', 'distributor.DistributorLevel/updateDistributorLevel');
    Route::delete('level/:id', 'distributor.DistributorLevel/deleteDistributorLevel');

    // 分销商订单
    Route::get('order/pages', 'distributor.DistributorOrder/getDistributorOrderPages');
    Route::get('order/list', 'distributor.DistributorOrder/getDistributorOrderList');
    // 分销商余额流水
    Route::get('balance-log/pages', 'distributor.DistributorBalance/getDistributorBalanceLogPages');
    Route::post('balance/modifyDistributorBalance', 'distributor.DistributorBalance/modifyDistributorBalance');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);