<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;


Route::group('merchant', function () {

    // 商户
    Route::post('merchant/audit/:id', 'merchant.Merchant/auditMerchant');
    Route::get('merchant/pages', 'merchant.Merchant/getMerchantPages');
    Route::post('merchant', 'merchant.Merchant/createMerchant');
    Route::get('merchant/:id', 'merchant.Merchant/getMerchantInfo');
    Route::put('merchant/:id', 'merchant.Merchant/updateMerchant');
    


    // 商户余额流水
    Route::get('balance-log/pages', 'merchant.MerchantBalance/getMerchantBalanceLogPages');
    Route::post('balance/modifyMerchantBalance', 'merchant.MerchantBalance/modifyMerchantBalance');




})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);