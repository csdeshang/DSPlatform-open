<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;


Route::group('trade', function () {

    // 商户支付配置  后台获取的数据默认是系统支付配置 merchant_id 为 0 时 获取系统支付配置
    // payment-configs/merchant (2段) 必须在 payment-configs/:id (2段) 前面 否则 GET /payment-configs/merchant 会被 payment-configs/:id 匹配
    Route::get('payment-configs/merchant', 'trade.TradePaymentConfig/getPaymentConfigByMerchant');
    Route::get('payment-configs/:id', 'trade.TradePaymentConfig/getPaymentConfigInfoById');
    Route::post('payment-configs', 'trade.TradePaymentConfig/createPaymentConfig');
    Route::put('payment-configs/:id', 'trade.TradePaymentConfig/updatePaymentConfig');
    Route::delete('payment-configs/:id', 'trade.TradePaymentConfig/deletePaymentConfig');

    
    // 交易支付记录
    Route::get('pay-logs/pages', 'trade.TradePayLog/getTradePayLogPages');

    // 交易退款记录
    Route::get('refund-logs/pages', 'trade.TradeRefundLog/getTradeRefundLogPages');

    // 交易转账记录
    Route::get('transfer-logs/pages', 'trade.TradeTransferLog/getTradeTransferLogPages');








})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);