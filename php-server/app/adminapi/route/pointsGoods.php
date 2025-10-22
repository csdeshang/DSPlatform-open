<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('points-goods', function () {

    // 积分商品管理
    
    Route::get('goods/pages', 'pointsGoods.PointsGoods/getPointsGoodsPages');
    Route::get('goods/:id', 'pointsGoods.PointsGoods/getPointsGoodsInfo');
    Route::post('goods', 'pointsGoods.PointsGoods/createPointsGoods');
    Route::put('goods/:id', 'pointsGoods.PointsGoods/updatePointsGoods');
    Route::delete('goods/:id', 'pointsGoods.PointsGoods/deletePointsGoods');

    // 积分商品分类管理
    Route::get('category/tree', 'pointsGoods.PointsGoodsCategory/getPointsGoodsCategoryTree');
    Route::get('category/:id', 'pointsGoods.PointsGoodsCategory/getPointsGoodsCategoryInfo');
    Route::post('category', 'pointsGoods.PointsGoodsCategory/createPointsGoodsCategory');
    Route::put('category/:id', 'pointsGoods.PointsGoodsCategory/updatePointsGoodsCategory');
    Route::delete('category/:id', 'pointsGoods.PointsGoodsCategory/deletePointsGoodsCategory');

    // 积分兑换订单管理
    
    Route::get('order/pages', 'pointsGoods.PointsGoodsOrder/getPointsGoodsOrderPages');
    Route::get('order/logs/:id', 'pointsGoods.PointsGoodsOrder/getPointsGoodsOrderLogs');
    // PS: 必须放在后面，要不会影响其他路由
    Route::get('order/:id', 'pointsGoods.PointsGoodsOrder/getPointsGoodsOrderInfo');
    Route::put('order/cancel/:id', 'pointsGoods.PointsGoodsOrder/cancelPointsGoodsOrder');
    Route::put('order/ship/:id', 'pointsGoods.PointsGoodsOrder/shipPointsGoodsOrder');
    Route::put('order/confirm/:id', 'pointsGoods.PointsGoodsOrder/confirmPointsGoodsOrder');
    // PS: 必须放在后面，要不会影响其他路由
    Route::put('order/:id', 'pointsGoods.PointsGoodsOrder/updatePointsGoodsOrder');
    Route::delete('order/:id', 'pointsGoods.PointsGoodsOrder/deletePointsGoodsOrder');

    // 积分商品评价管理
    Route::get('evaluate/pages', 'pointsGoods.PointsGoodsEvaluate/getPointsGoodsEvaluatePages');
    Route::put('evaluate/toggle', 'pointsGoods.PointsGoodsEvaluate/togglePointsGoodsEvaluateField');
    Route::put('evaluate/reply', 'pointsGoods.PointsGoodsEvaluate/replyPointsGoodsEvaluate');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
