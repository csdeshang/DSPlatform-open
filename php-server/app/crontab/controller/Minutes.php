<?php

namespace app\crontab\controller;

use app\deshang\base\controller\BaseApiController;

use app\common\dao\order\TblOrderDao;
use app\common\dao\goods\TblGoodsFlashsaleDao;
use app\common\dao\store\TblStoreCouponDao;

use app\common\enum\order\TblOrderEnum;
use app\common\enum\goods\TblGoodsFlashsaleEnum;
use app\common\enum\store\TblStoreCouponEnum;

use app\deshang\service\order\DeshangTblOrderService;

class Minutes extends BaseApiController
{


    public function index()
    {
        // 自动取消订单
        $this->autoCancelOrder();

        // 自动确认收货
        $this->autoConfirmOrder();

        // 自动设置优惠券状态
        $this->autoSetCoupon();

        // 自动设置促销活动状态
        $this->autoSetPromotion();
    }



    // 自动取消订单
    public function autoCancelOrder()
    {
        // 获取是否开启自动取消订单
        $auto_cancel_order_enabled = sysConfig('order_auto:auto_cancel_order_enabled');

        if ($auto_cancel_order_enabled == 1) {
            // 获取自动取消订单时间
            $auto_cancel_order_hours = sysConfig('order_auto:auto_cancel_order_hours');
            $auto_cancel_order_time = $auto_cancel_order_hours * 60 * 60;

            // 获取订单
            $condition = [];
            $condition[] = ['order_status', '=', TblOrderEnum::ORDER_STATUS_PENDING];
            $condition[] = ['add_time', '<', time() - $auto_cancel_order_time];
            $order_list = (new TblOrderDao)->getOrderList($condition);

            if (!empty($order_list)) {
                foreach ($order_list as $order) {
                    // 取消订单
                    (new DeshangTblOrderService)->changeTblOrderClose($order, 'system');
                }
            }
        }
    }


    // 自动确认收货
    public function autoConfirmOrder()
    {
        // 获取是否开启自动确认收货
        $auto_confirm_order_enabled = sysConfig('order_auto:auto_confirm_order_enabled');

        if ($auto_confirm_order_enabled == 1) {
            // 获取自动确认收货时间
            $auto_confirm_order_hours = sysConfig('order_auto:auto_confirm_order_hours');
            $auto_confirm_order_time = $auto_confirm_order_hours * 60 * 60;

            // 获取订单
            $condition = [];
            $condition[] = ['order_status', '=', TblOrderEnum::ORDER_STATUS_ACCEPTED];
            $condition[] = ['delivery_time', '<', time() - $auto_confirm_order_time];
            // 正在退款的订单，不自动确认收货
            $condition[] = ['refunding_count', '=', 0];
            $order_list = (new TblOrderDao)->getOrderList($condition);

            if (!empty($order_list)) {
                foreach ($order_list as $order) {
                    // 确认收货
                    (new DeshangTblOrderService)->changeTblOrderConfirm($order, 'system');
                }
            }
        }
    }


    // 自动设置优惠券状态
    public function autoSetCoupon() {

        $now_time = time();

        // 1. 处理优惠券
        // 1.1 处理未开始->进行中的优惠券
        $condition = [];
        $condition[] = ['status', '=', TblStoreCouponEnum::STATUS_NOT_START];
        $condition[] = ['start_time', '<=', $now_time];
        $condition[] = ['end_time', '>=', $now_time];
        $coupon_ids = (new TblStoreCouponDao)->getStoreCouponColumn($condition ,'id');
        if (!empty($coupon_ids)) {
            (new TblStoreCouponDao)->updateStoreCoupon([['id', 'in', $coupon_ids]], ['status' => TblStoreCouponEnum::STATUS_START]);
        }

        // 1.2 处理进行中->已结束的优惠券
        $condition = [];
        $condition[] = ['status', '=', TblStoreCouponEnum::STATUS_START];
        $condition[] = ['end_time', '<', $now_time];
        $coupon_ids = (new TblStoreCouponDao)->getStoreCouponColumn($condition ,'id');
        if (!empty($coupon_ids)) {
            (new TblStoreCouponDao)->updateStoreCoupon([['id', 'in', $coupon_ids]], ['status' => TblStoreCouponEnum::STATUS_END]);
        }





    }


    // 自动设置促销活动状态
    public function autoSetPromotion()
    {

        $now_time = time();



        // 2. 处理商品限时折扣
        // 2.1 处理未开始->进行中的限时折扣
        $condition = [];
        $condition[] = ['status', '=', TblGoodsFlashsaleEnum::STATUS_NOT_START];
        $condition[] = ['start_time', '<=', $now_time];
        $condition[] = ['end_time', '>=', $now_time];
        $flashsale_ids = (new TblGoodsFlashsaleDao)->getGoodsFlashsaleColumn($condition ,'id');
        if (!empty($flashsale_ids)) {
            (new TblGoodsFlashsaleDao)->updateGoodsFlashsale([['id', 'in', $flashsale_ids]], ['status' => TblGoodsFlashsaleEnum::STATUS_START]);
        }

        // 2.2 处理进行中->已结束的限时折扣
        $condition = [];
        $condition[] = ['status', '=', TblGoodsFlashsaleEnum::STATUS_START];
        $condition[] = ['end_time', '<', $now_time];
        $flashsale_ids = (new TblGoodsFlashsaleDao)->getGoodsFlashsaleColumn($condition ,'id');
        if (!empty($flashsale_ids)) {
            (new TblGoodsFlashsaleDao)->updateGoodsFlashsale([['id', 'in', $flashsale_ids]], ['status' => TblGoodsFlashsaleEnum::STATUS_END]);
        }




    }
}
