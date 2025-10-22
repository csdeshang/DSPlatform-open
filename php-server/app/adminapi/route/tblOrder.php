<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('tbl-order', function () {


    // 多平台通用 订单分页
    Route::get('order/pages', 'tblOrder.tblOrder/getTblOrderPages');

    // 根据订单id 获取订单信息
    Route::get('order/info/:id', 'tblOrder.tblOrder/getTblOrderInfo');



    // 获取订单商品列表
    Route::get('goods/list', 'tblOrder.tblOrder/getTblOrderGoodsList');

    // 获取订单商品分页
    Route::get('goods/pages', 'tblOrder.tblOrder/getTblOrderGoodsPages');

    // 获取订单日志
    Route::get('logs', 'tblOrder.tblOrder/getTblOrderLogList');

    // 获取订单支付记录
    Route::get('pay-logs', 'tblOrder.tblOrder/getTblOrderPayLogList');


    // 退款
    // 获取订单退款分页
    Route::get('refund/pages', 'tblOrder.TblOrderRefund/getTblOrderRefundPages');
    // 获取订单退款列表
    Route::get('refund/list', 'tblOrder.TblOrderRefund/getTblOrderRefundList');
    // 获取订单退款详情
    Route::get('refund/info/:id', 'tblOrder.TblOrderRefund/getTblOrderRefundInfo');
    // 重新发起退款 当退款状态为 60 或者 80 时，可以重新发起退款， 表示订单相关的处理已完成，但是金额未退款
    Route::post('refund/retry/:id', 'tblOrder.TblOrderRefund/retryTblOrderRefund');
    // 获取订单退款操作日志列表
    Route::get('refund-log/list/:id', 'tblOrder.TblOrderRefund/getTblOrderRefundLogList');


    // 交付订单
    Route::get('delivery/pages', 'tblOrder.TblOrderDelivery/getTblOrderDeliveryPages');




})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
