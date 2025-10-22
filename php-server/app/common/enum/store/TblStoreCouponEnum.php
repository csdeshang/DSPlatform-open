<?php

namespace app\common\enum\store;

/**
 * 店铺优惠券相关
 */
class TblStoreCouponEnum
{
    // 优惠券类型
    const TYPE_DIRECT_REDUCTION = 1; // 直减券
    const TYPE_DISCOUNT = 2; // 折扣券




    /**
     * 获取优惠券类型列表
     * @return array
     */
    public static function getStoreCouponTypeDict(): array
    {
        return [
            self::TYPE_DIRECT_REDUCTION => '直减券',
            self::TYPE_DISCOUNT => '折扣券',

        ];
    }

    /**
     * 获取优惠券类型描述
     * @param int $value
     * @return string
     */
    public static function getStoreCouponTypeDesc(int $value): string
    {
        $data = self::getStoreCouponTypeDict();
        return $data[$value] ?? '未知类型';
    }



    // 优惠券领取类型
    const CLAIM_TYPE_MANUAL = 1; // 手动领取
    const CLAIM_TYPE_REGISTER = 2; // 注册赠送
    const CLAIM_TYPE_ORDER = 3; // 订单完成赠送
    const CLAIM_TYPE_ACTIVE = 4; // 主动发放
    /**
     * 获取优惠券领取类型列表
     * @return array
     */
    public static function getStoreCouponClaimTypeDict(): array
    {
        return [
            self::CLAIM_TYPE_MANUAL => '手动领取',
            self::CLAIM_TYPE_REGISTER => '注册赠送',
            self::CLAIM_TYPE_ORDER => '下单赠送',
            self::CLAIM_TYPE_ACTIVE => '主动发放',
        ];
    }

    /**
     * 获取优惠券领取类型描述
     * @param int $value
     * @return string
     */
    public static function getStoreCouponClaimTypeDesc(int $value): string
    {
        $data = self::getStoreCouponClaimTypeDict();
        return $data[$value] ?? '未知类型';
    }


    // 优惠券状态
    const STATUS_NOT_START = 0; // 未开始
    const STATUS_START = 1; // 进行中
    const STATUS_END = 2; // 已结束
    const STATUS_CLOSE = 3; // 关闭
    /**
     * 获取优惠券状态列表
     * @return array
     */
    public static function getStoreCouponStatusDict(): array
    {
        return [
            self::STATUS_NOT_START => '未开始',
            self::STATUS_START => '进行中',
            self::STATUS_END => '已结束',
            self::STATUS_CLOSE => '已关闭',
        ];
    }

    /**
     * 获取优惠券状态描述
     * @param int $value
     * @return string
     */
    public static function getStatusDesc(int $value): string
    {
        $data = self::getStoreCouponStatusDict();
        return $data[$value] ?? '未知状态';
    }





}
