<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('points-goods', function () {

    // 积分商品管理
    // goods/pages (2段) 必须在 goods/:id (2段) 前面 否则 GET /goods/pages 会被 goods/:id 匹配
    Route::get('goods/pages', 'pointsGoods.PointsGoods/getPointsGoodsPages');
    Route::post('goods', 'pointsGoods.PointsGoods/createPointsGoods');
    Route::get('goods/:id', 'pointsGoods.PointsGoods/getPointsGoodsInfo');
    Route::put('goods/:id', 'pointsGoods.PointsGoods/updatePointsGoods');
    Route::delete('goods/:id', 'pointsGoods.PointsGoods/deletePointsGoods');

    // 积分商品分类管理
    // categories/tree (2段) 必须在 categories/:id (2段) 前面 否则 GET /categories/tree 会被 categories/:id 匹配
    Route::get('categories/tree', 'pointsGoods.PointsGoodsCategory/getPointsGoodsCategoryTree');
    Route::post('categories', 'pointsGoods.PointsGoodsCategory/createPointsGoodsCategory');
    Route::get('categories/:id', 'pointsGoods.PointsGoodsCategory/getPointsGoodsCategoryInfo');
    Route::put('categories/:id', 'pointsGoods.PointsGoodsCategory/updatePointsGoodsCategory');
    Route::delete('categories/:id', 'pointsGoods.PointsGoodsCategory/deletePointsGoodsCategory');

    // 积分兑换订单管理
    // orders/pages (2段) 必须在 orders/:id (2段) 前面 否则 GET /orders/pages 会被 orders/:id 匹配
    Route::get('orders/pages', 'pointsGoods.PointsGoodsOrder/getPointsGoodsOrderPages');
    // orders/:id/logs (3段) 必须在 orders/:id (2段) 前面 否则 GET /orders/123/logs 会被 orders/:id 匹配
    Route::get('orders/:id/logs', 'pointsGoods.PointsGoodsOrder/getPointsGoodsOrderLogs');
    // orders/:id/cancel, orders/:id/ship, orders/:id/confirm (3段) 必须在 orders/:id (2段) 前面
    Route::post('orders/:id/cancel', 'pointsGoods.PointsGoodsOrder/cancelPointsGoodsOrder');
    Route::post('orders/:id/ship', 'pointsGoods.PointsGoodsOrder/shipPointsGoodsOrder');
    Route::post('orders/:id/confirm', 'pointsGoods.PointsGoodsOrder/confirmPointsGoodsOrder');
    Route::get('orders/:id', 'pointsGoods.PointsGoodsOrder/getPointsGoodsOrderInfo');
    Route::put('orders/:id', 'pointsGoods.PointsGoodsOrder/updatePointsGoodsOrder');
    Route::delete('orders/:id', 'pointsGoods.PointsGoodsOrder/deletePointsGoodsOrder');

    // 积分商品评价管理
    // evaluates/pages (2段) 必须在 evaluates/:id (2段) 前面 否则 GET /evaluates/pages 会被 evaluates/:id 匹配
    Route::get('evaluates/pages', 'pointsGoods.PointsGoodsEvaluate/getPointsGoodsEvaluatePages');
    Route::patch('evaluates/:id/toggle-field', 'pointsGoods.PointsGoodsEvaluate/togglePointsGoodsEvaluateField');
    Route::post('evaluates/:id/reply', 'pointsGoods.PointsGoodsEvaluate/replyPointsGoodsEvaluate');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
