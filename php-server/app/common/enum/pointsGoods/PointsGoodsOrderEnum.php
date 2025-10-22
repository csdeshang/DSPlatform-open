<?php

namespace app\common\enum\pointsGoods;

/**
 * 积分兑换订单相关枚举
 */
class PointsGoodsOrderEnum
{
    // 订单状态
    const ORDER_STATUS_CANCELLED = 0; // 已取消
    const ORDER_STATUS_PENDING = 10; // 待发货
    const ORDER_STATUS_SHIPPED = 20; // 已发货
    const ORDER_STATUS_RECEIVED = 30; // 已收货
    const ORDER_STATUS_COMPLETED = 40; // 已完成

    // 配送方式
    const DELIVERY_METHOD_EXPRESS = 'express'; // 快递
    const DELIVERY_METHOD_DELIVERY = 'delivery'; // 自提

    // 获取订单状态字典
    public static function getOrderStatusDict(): array
    {
        return [
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_PENDING => '待发货',
            self::ORDER_STATUS_SHIPPED => '已发货',
            self::ORDER_STATUS_RECEIVED => '已收货',
            self::ORDER_STATUS_COMPLETED => '已完成',
        ];
    }

    // 获取订单状态描述
    public static function getOrderStatusDesc($value): string
    {
        $data = self::getOrderStatusDict();
        return $data[$value] ?? '未知状态';
    }

    // 获取配送方式字典
    public static function getDeliveryMethodDict(): array
    {
        return [
            self::DELIVERY_METHOD_EXPRESS => '快递',
            self::DELIVERY_METHOD_DELIVERY => '上门自提',
        ];
    }

    // 获取配送方式描述
    public static function getDeliveryMethodDesc($value): string
    {
        $data = self::getDeliveryMethodDict();
        return $data[$value] ?? '未知方式';
    }
}
