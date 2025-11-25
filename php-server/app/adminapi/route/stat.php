<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('stat', function () {
    // 用户统计 概览
    Route::get('users/overview', 'stat.StatUser/getStatUserOverview');
    // 商户统计 概览
    Route::get('merchants/overview', 'stat.StatMerchant/getStatMerchantOverview');
    // 商品统计 概览
    Route::get('goods/overview', 'stat.StatGoods/getStatGoodsOverview');
    // 订单统计 概览
    Route::get('orders/overview', 'stat.StatOrder/getStatOrderOverview');
    // 店铺统计 概览
    Route::get('stores/overview', 'stat.StatStore/getStatStoreOverview');
    // 分销商统计 概览
    Route::get('distributors/overview', 'stat.StatDistributor/getStatDistributorOverview');



    
})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);