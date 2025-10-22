<?php

namespace app\common\enum\user;

/**
 * 会员积分相关
 */
class UserPointsEnum
{
    
    // 变动方式
    const MODE_INCREASE = 1; // 增加
    const MODE_DECREASE = 2; // 减少


    // 积分相关类别
    const TYPE_SYSTEM = 'system'; // 系统
    const TYPE_LOGIN = 'login'; // 登录
    const TYPE_REGISTER = 'register'; // 注册
    const TYPE_ORDER_PAY = 'order_pay'; // 订单支付
    const TYPE_GOODS_COMMENT = 'goods_comment'; // 评论
    const TYPE_INVITE = 'invite'; // 邀请
    const TYPE_STORE_COUPON = 'store_coupon'; // 领取店铺优惠券
    const TYPE_POINTS_GOODS_ORDER = 'points_goods_order'; // 积分商品订单



    // 获取变动类型列表
    public static function getChangeTypeDict(): array
    {
        return [
            self::TYPE_SYSTEM => '系统',
            self::TYPE_LOGIN => '登录',
            self::TYPE_REGISTER => '注册',
            self::TYPE_ORDER_PAY => '订单支付',
            self::TYPE_GOODS_COMMENT => '评论',
            self::TYPE_INVITE => '邀请',
            self::TYPE_STORE_COUPON => '领取店铺优惠券',
            self::TYPE_POINTS_GOODS_ORDER => '积分商品订单',
        ];
    }

    // 获取变动类型描述
    public static function getChangeTypeDesc($value): string
    {
        $data = self::getChangeTypeDict();
        return $data[$value] ?? '未知类型';
    }


    // 获取变动方式列表
    public static function getChangeModeDict(): array
    {
        return [
            self::MODE_INCREASE => '增加',
            self::MODE_DECREASE => '减少',
        ];
    }
    // 获取变动方式描述
    public static function getChangeModeDesc($value): string
    {
        $data = self::getChangeModeDict();
        return $data[$value] ?? '未知方式';
    }


}