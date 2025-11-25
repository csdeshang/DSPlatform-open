<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;


Route::group('merchant', function () {

    // 商户
    // merchants/:id/audit (3段) 必须在 merchants/:id (2段) 前面 防止将来添加 PATCH merchants/:id 时产生冲突
    Route::patch('merchants/:id/audit', 'merchant.Merchant/auditMerchant');
    // merchants/pages (2段) 必须在 merchants/:id (2段) 前面 否则 GET /merchants/pages 会被 merchants/:id 匹配
    Route::get('merchants/pages', 'merchant.Merchant/getMerchantPages');
    Route::get('merchants/:id', 'merchant.Merchant/getMerchantInfo');
    // merchants/:id/balance (3段) 必须在 merchants/:id (2段) 前面 否则 PUT /merchants/123/balance 会被 merchants/:id 匹配
    Route::put('merchants/:id/balance', 'merchant.MerchantBalance/modifyMerchantBalance');
    Route::put('merchants/:id', 'merchant.Merchant/updateMerchant');
    Route::post('merchants', 'merchant.Merchant/createMerchant');
    


    // 商户余额流水
    // balance-logs/pages (2段) 必须在 balance-logs/:id (2段) 前面（如果有的话）
    Route::get('balance-logs/pages', 'merchant.MerchantBalance/getMerchantBalanceLogPages');




})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);