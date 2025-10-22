<?php

namespace app\common\enum\store;

/**
 * 店铺优惠券相关
 */
class TblStoreCouponUserEnum
{
    





    // 用户优惠券状态
    const USER_STATUS_UNUSED = 0; // 未使用
    const USER_STATUS_USED = 1; // 已使用
    const USER_STATUS_EXPIRED = 2; // 已过期
    /**
     * 获取用户优惠券状态列表
     * @return array
     */
    public static function getStoreCouponUserStatusDict(): array
    {
        return [
            self::USER_STATUS_UNUSED => '未使用',
            self::USER_STATUS_USED => '已使用',
            self::USER_STATUS_EXPIRED => '已过期',
        ];
    }

    /**
     * 获取用户优惠券状态描述
     * @param int $value
     * @return string
     */
    public static function getStoreCouponUserStatusDesc(int $value): string
    {
        $data = self::getStoreCouponUserStatusDict();
        return $data[$value] ?? '未知状态';
    }
}
