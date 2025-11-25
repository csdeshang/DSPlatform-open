<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('tbl-order', function () {


    // 多平台通用 订单分页
    // orders/pages (2段) 必须在 orders/:id (2段) 前面 否则 GET /orders/pages 会被 orders/:id 匹配
    Route::get('orders/pages', 'tblOrder.TblOrder/getTblOrderPages');
    // 根据订单id 获取订单信息
    Route::get('orders/:id', 'tblOrder.TblOrder/getTblOrderInfo');



    // 获取订单商品列表
    Route::get('goods/list', 'tblOrder.TblOrder/getTblOrderGoodsList');

    // 获取订单商品分页
    Route::get('goods/pages', 'tblOrder.TblOrder/getTblOrderGoodsPages');

    // 获取订单日志（独立资源，支持跨订单查询）
    Route::get('order-logs', 'tblOrder.TblOrder/getTblOrderLogList');

    // 获取订单支付记录（独立资源，支持跨订单查询）
    Route::get('order-pay-logs', 'tblOrder.TblOrder/getTblOrderPayLogList');


    // 退款
    // refunds/pages (2段) 必须在 refunds/:id (2段) 前面 否则 GET /refunds/pages 会被 refunds/:id 匹配
    // 获取订单退款分页
    Route::get('refunds/pages', 'tblOrder.TblOrderRefund/getTblOrderRefundPages');
    // refunds/list (2段) 必须在 refunds/:id (2段) 前面 否则 GET /refunds/list 会被 refunds/:id 匹配
    // 获取订单退款列表
    Route::get('refunds/list', 'tblOrder.TblOrderRefund/getTblOrderRefundList');
    // refunds/:id/retry (3段) 必须在 refunds/:id (2段) 前面 否则 POST /refunds/123/retry 会被 refunds/:id 匹配
    // 重新发起退款 当退款状态为 60 或者 80 时，可以重新发起退款， 表示订单相关的处理已完成，但是金额未退款
    Route::post('refunds/:id/retry', 'tblOrder.TblOrderRefund/retryTblOrderRefund');
    // refunds/:id/logs (3段) 必须在 refunds/:id (2段) 前面 否则 GET /refunds/123/logs 会被 refunds/:id 匹配
    // 获取订单退款操作日志列表
    Route::get('refunds/:id/logs', 'tblOrder.TblOrderRefund/getTblOrderRefundLogList');
    // 获取订单退款详情
    Route::get('refunds/:id', 'tblOrder.TblOrderRefund/getTblOrderRefundInfo');


    // 交付订单
    // deliveries/pages (2段) 必须在 deliveries/:id (2段) 前面 否则 GET /deliveries/pages 会被 deliveries/:id 匹配
    Route::get('deliveries/pages', 'tblOrder.TblOrderDelivery/getTblOrderDeliveryPages');




})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
