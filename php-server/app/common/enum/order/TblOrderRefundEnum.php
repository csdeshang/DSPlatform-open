<?php

namespace app\common\enum\order;


/**
 * 订单退款相关枚举
 */
class TblOrderRefundEnum
{
    // 退款类型
    const TYPE_ONLY_REFUND = 1; // 仅退款
    const TYPE_REFUND_GOODS = 2; // 退货退款
    const TYPE_SYSTEM_REFUND = 3; // 系统退款

    /**
     * 获取退款类型字典
     * 
     * @return array 退款类型字典
     */
    public static function getAllRefundTypeDict(): array
    {
        return [
            self::TYPE_ONLY_REFUND => '仅退款',
            self::TYPE_REFUND_GOODS => '退货退款',
            self::TYPE_SYSTEM_REFUND => '系统退款'
        ];
    }

    /**
     * 获取退款类型描述
     * 
     * @param int $type 退款类型
     * @return string 退款类型描述
     */
    public static function getRefundTypeDesc(int $type): string
    {
        $dict = self::getAllRefundTypeDict();
        return $dict[$type] ?? '未知类型';
    }

    // 退款状态
    const STATUS_STORE_APPLYING = 0; // 店铺发起退款
    const STATUS_USER_APPLYING = 10; // 用户申请中
    const STATUS_STORE_AGREED = 20; // 店铺同意
    const STATUS_STORE_REJECTED = 30; // 店铺拒绝
    const STATUS_USER_RETURNED = 40; // 用户已退货
    const STATUS_STORE_RECEIVED = 50; // 店铺已收货
    const STATUS_REFUND_PROCESSING = 60; // 退款处理中
    const STATUS_REFUND_SUCCESS = 70; // 退款成功
    const STATUS_REFUND_FAILED = 80; // 退款失败
    const STATUS_CLOSED = 90; // 已关闭
    const STATUS_CANCELED = 100; // 已取消

    /**
     * 获取退款状态字典
     * 
     * @return array 退款状态字典
     */
    public static function getRefundStatusDict(): array
    {
        return [
            self::STATUS_STORE_APPLYING => '店铺发起退款',
            self::STATUS_USER_APPLYING => '用户申请中',
            self::STATUS_STORE_AGREED => '店铺同意',
            self::STATUS_STORE_REJECTED => '店铺拒绝',
            self::STATUS_USER_RETURNED => '用户已退货',
            self::STATUS_STORE_RECEIVED => '店铺已收货',
            self::STATUS_REFUND_PROCESSING => '退款处理中',
            self::STATUS_REFUND_SUCCESS => '退款成功',
            self::STATUS_REFUND_FAILED => '退款失败',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_CANCELED => '已取消'
        ];
    }

    /**
     * 获取退款状态描述
     * 
     * @param int $status 退款状态
     * @return string 退款状态描述
     */
    public static function getRefundStatusDesc(int $status): string
    {
        $dict = self::getRefundStatusDict();
        return $dict[$status] ?? '未知状态';
    }


    // 获取Mall退款状态字典
    public static function getMallRefundStatusDict(): array
    {
        return [
            self::STATUS_STORE_APPLYING => '店铺发起退款',
            self::STATUS_USER_APPLYING => '用户申请中',
            self::STATUS_STORE_AGREED => '店铺同意',
            self::STATUS_STORE_REJECTED => '店铺拒绝',
            self::STATUS_USER_RETURNED => '用户已退货',
            self::STATUS_STORE_RECEIVED => '店铺已收货',
            self::STATUS_REFUND_PROCESSING => '退款处理中',
            self::STATUS_REFUND_SUCCESS => '退款成功',
            self::STATUS_REFUND_FAILED => '退款失败',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_CANCELED => '已取消'
        ];
    }


    // 获取外卖退款状态字典
    public static function getFoodRefundStatusDict(): array
    {
        return [
            self::STATUS_STORE_APPLYING => '店铺发起退款',
            self::STATUS_USER_APPLYING => '用户申请中',
            self::STATUS_STORE_AGREED => '店铺同意',
            self::STATUS_STORE_REJECTED => '店铺拒绝',
            self::STATUS_USER_RETURNED => '用户已退货',
            self::STATUS_STORE_RECEIVED => '店铺已收货',
            self::STATUS_REFUND_PROCESSING => '退款处理中',
            self::STATUS_REFUND_SUCCESS => '退款成功',
            self::STATUS_REFUND_FAILED => '退款失败',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_CANCELED => '已取消'
        ];
    }

    // 获取客满速退款状态字典
    public static function getKmsRefundStatusDict(): array
    {
        return [
            self::STATUS_STORE_APPLYING => '店铺发起退款',
            self::STATUS_USER_APPLYING => '用户申请中',
            self::STATUS_STORE_AGREED => '店铺同意',
            self::STATUS_STORE_REJECTED => '店铺拒绝',
            self::STATUS_REFUND_PROCESSING => '退款处理中',
            self::STATUS_REFUND_SUCCESS => '退款成功',
            self::STATUS_REFUND_FAILED => '退款失败',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_CANCELED => '已取消'
        ];
    }

    // 获取家政退款状态字典
    public static function getHouseRefundStatusDict(): array
    {
        return [
            self::STATUS_STORE_APPLYING => '店铺发起退款',
            self::STATUS_USER_APPLYING => '用户申请中',
            self::STATUS_STORE_AGREED => '店铺同意',
            self::STATUS_STORE_REJECTED => '店铺拒绝',
            self::STATUS_REFUND_PROCESSING => '退款处理中',
            self::STATUS_REFUND_SUCCESS => '退款成功',
            self::STATUS_REFUND_FAILED => '退款失败',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_CANCELED => '已取消'
        ];
    }



    // 退款方式
    const REFUND_METHOD_ORIGINAL = 1; // 原路退回
    const REFUND_METHOD_BALANCE = 2; // 退回余额
    const REFUND_METHOD_MANUAL = 3; // 人工处理

    /**
     * 获取退款方式字典
     * 
     * @return array 退款方式字典
     */
    public static function getRefundMethodDict(): array
    {
        return [
            self::REFUND_METHOD_ORIGINAL => '原路退回',
            self::REFUND_METHOD_BALANCE => '退回余额',
            self::REFUND_METHOD_MANUAL => '人工处理'
        ];
    }

    /**
     * 获取退款方式描述
     * 
     * @param int $method 退款方式
     * @return string 退款方式描述
     */
    public static function getRefundMethodDesc($method): string
    {
        $dict = self::getRefundMethodDict();
        return $dict[$method] ?? '未知方式';
    }
}
