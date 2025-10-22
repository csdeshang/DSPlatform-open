<?php

namespace app\common\enum\order;


/**
 * 订单相关枚举
 */
class TblOrderEnum
{
    // 订单状态 
    const ORDER_STATUS_CLOSED = 0;  // 关闭  待支付则可关闭订单  ，不再需要进一步处理
    const ORDER_STATUS_CANCELLED = 10;  // 取消 已付款则可取消订单 ， 需要处理退款
    const ORDER_STATUS_PENDING = 20;  // 待支付
    const ORDER_STATUS_PAID = 30; // 已付款  
    const ORDER_STATUS_ACCEPTED = 40; // 已接受  商家接受订单  等待物流取件  骑手上门抢单 
    const ORDER_STATUS_CONFIRMED = 50;  // 店铺已确认  (针对外卖 商家商品已准备好)

    const ORDER_STATUS_COMPLETED = 100;  // 订单完成,用户确认收货



    // 获取所有订单状态列表
    public static function getAllOrderStatusDict(): array
    {
        return [
            self::ORDER_STATUS_CLOSED => '已关闭',
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_PENDING => '待支付',
            self::ORDER_STATUS_PAID => '待发货', //已付款
            self::ORDER_STATUS_ACCEPTED => '已发货', // 确认 商家确认商品准备好
            // self::ORDER_STATUS_CONFIRMED => '派送中', // 店铺已确认 (针对外卖 商家商品已准备好)
            self::ORDER_STATUS_COMPLETED => '已完成',
        ];
    }

    // 获取Mall订单状态列表
    public static function getMallOrderStatusDict(): array
    {
        return [
            self::ORDER_STATUS_CLOSED => '已关闭',
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_PENDING => '待支付',
            self::ORDER_STATUS_PAID => '待发货', //已付款
            self::ORDER_STATUS_ACCEPTED => '已发货', // 确认 商家确认商品准备好
            self::ORDER_STATUS_COMPLETED => '已完成',
        ];
    }

    // 获取Mall订单状态描述
    public static function getMallOrderStatusDesc($value): string
    {
        $data = self::getMallOrderStatusDict();
        return $data[$value] ?? '未知订单状态';
    }
    // 获取外卖订单状态列表
    public static function getFoodOrderStatusDict(): array
    {
        return [
            self::ORDER_STATUS_CLOSED => '已关闭',
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_PENDING => '待支付',
            self::ORDER_STATUS_PAID => '待接单', //已付款
            self::ORDER_STATUS_ACCEPTED => '店铺已接单', //已接单
            self::ORDER_STATUS_CONFIRMED => '派送中', //商家确认商品准备好，等待骑手接单
            self::ORDER_STATUS_COMPLETED => '已完成', //订单完成
        ];
    }

    // 获取外卖订单状态描述
    public static function getFoodOrderStatusDesc($value): string
    {
        $data = self::getFoodOrderStatusDict();
        return $data[$value] ?? '未知订单状态';
    }

    // 家政订单状态列表
    public static function getHouseOrderStatusDict(): array
    {
        return [
            self::ORDER_STATUS_CLOSED => '已关闭',
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_PENDING => '待支付',
            self::ORDER_STATUS_PAID => '待出发', //已付款
            self::ORDER_STATUS_ACCEPTED => '服务中', //已接单
            self::ORDER_STATUS_COMPLETED => '已完成', //订单完成
        ];
    }

    // 获取家政订单状态描述
    public static function getHouseOrderStatusDesc($value): string
    {
        $data = self::getHouseOrderStatusDict();
        return $data[$value] ?? '未知订单状态';
    }


    // KMS订单状态列表
    public static function getKmsOrderStatusDict(): array
    {
        return [
            self::ORDER_STATUS_CLOSED => '已关闭',
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_PENDING => '待支付',
            self::ORDER_STATUS_PAID => '待接单', //已付款
            self::ORDER_STATUS_ACCEPTED => '已发货', //已接单
            self::ORDER_STATUS_COMPLETED => '已完成',
        ];
    }

    // 获取KMS订单状态描述
    public static function getKmsOrderStatusDesc($value): string
    {
        $data = self::getKmsOrderStatusDict();
        return $data[$value] ?? '未知订单状态';
    }



    // 交付方式   delivery_method 字段
    const DELIVERY_VIRTUAL = 'virtual';           // 虚拟交付(虚拟商品)
    const DELIVERY_EXPRESS = 'express';           // 快递交付(实物商品)
    const DELIVERY_IN_STORE = 'in_store';         // 到店自提(实物商品)
    const DELIVERY_RIDER = 'rider';               // 骑手配送(外卖类)
    const DELIVERY_DINE_IN = 'dine_in';           // 堂食(外卖类)
    const DELIVERY_ERRANDS = 'errands';           // 跑腿服务(外卖类)
    const DELIVERY_TECHNICIAN = 'technician';  // 上门服务(家政,维修类)
    const DELIVERY_ONSITE = 'onsite';             // 现场服务(酒店,景点类)

    // const DELIVERY_SAME_CITY = 'same_city';       // 同城配送(第三方接口)


    // 获取交付方式字典
    public static function getAllOrderDeliveryDict(): array
    {
        return [
            self::DELIVERY_VIRTUAL => '虚拟交付',
            self::DELIVERY_EXPRESS => '快递交付',
            self::DELIVERY_IN_STORE => '到店自提',
            self::DELIVERY_RIDER => '骑手配送',
            self::DELIVERY_DINE_IN => '堂食',
            self::DELIVERY_ERRANDS => '跑腿服务',
            self::DELIVERY_TECHNICIAN => '上门服务',
            self::DELIVERY_ONSITE => '现场服务',
        ];
    }

    // 获取交付方式描述
    public static function getAllDeliveryDesc($value): string
    {
        $data = self::getAllOrderDeliveryDict();
        return $data[$value] ?? '未交付';
    }


    // 获取交付方式字典
    public static function getMallOrderDeliveryDict(): array
    {
        return [
            self::DELIVERY_VIRTUAL => '虚拟交付',
            self::DELIVERY_EXPRESS => '快递交付',
            self::DELIVERY_IN_STORE => '到店自提',
        ];
    }

    // 获取外卖交付方式字典
    public static function getFoodOrderDeliveryDict(): array
    {
        return [
            self::DELIVERY_RIDER => '骑手配送',
            self::DELIVERY_DINE_IN => '堂食',
            // self::DELIVERY_ERRANDS => '跑腿服务',
        ];
    }


    // 获取家政交付方式字典
    public static function getHouseOrderDeliveryDict(): array
    {
        return [
            self::DELIVERY_TECHNICIAN => '上门服务',
        ];
    }

    // 获取视频教育交付方式字典
    public static function getKmsOrderDeliveryDict(): array
    {
        return [
            self::DELIVERY_VIRTUAL => '虚拟交付',
        ];
    }


    // 退款状态
    const REFUND_STATUS_NONE = 0; // 无退款
    const REFUND_STATUS_PARTIAL_REFUNDED = 1; // 部分退款
    const REFUND_STATUS_FULL_REFUNDED = 2; // 全部退款

    // 获取退款状态字典
    public static function getOrderRefundStatusDict(): array
    {
        return [
            self::REFUND_STATUS_NONE => '无退款',
            self::REFUND_STATUS_PARTIAL_REFUNDED => '部分退款',
            self::REFUND_STATUS_FULL_REFUNDED => '全部退款',
        ];
    }

    // 获取退款状态描述
    public static function getOrderRefundStatusDesc($value): string
    {
        $data = self::getOrderRefundStatusDict();
        return $data[$value] ?? '未知退款状态';
    }

}
