<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;


Route::group('trade', function () {

    // 商户支付配置  后台获取的数据默认是系统支付配置 merchant_id 为 0 时 获取系统支付配置
    Route::get('payment-config/merchant', 'trade.TradePaymentConfig/getPaymentConfigByMerchant');
    
    Route::get('payment-config/:id', 'trade.TradePaymentConfig/getPaymentConfigInfoById');
    Route::post('payment-config', 'trade.TradePaymentConfig/createPaymentConfig');
    Route::put('payment-config/:id', 'trade.TradePaymentConfig/updatePaymentConfig');
    Route::delete('payment-config/:id', 'trade.TradePaymentConfig/deletePaymentConfig');

    
    // 交易支付记录
    Route::get('pay-log/pages', 'trade.TradePayLog/getTradePayLogPages');

    // 交易退款记录
    Route::get('refund-log/pages', 'trade.TradeRefundLog/getTradeRefundLogPages');

    // 交易转账记录
    Route::get('transfer-log/pages', 'trade.TradeTransferLog/getTradeTransferLogPages');








})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);