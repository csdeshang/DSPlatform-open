<?php

namespace app\common\enum\distributor;

class DistributorOrderEnum
{
    // 佣金状态
    const COMMISSION_STATUS_PENDING = 0; // 待付款
    const COMMISSION_STATUS_WAIT = 1; // 待结算 (已付款至订单不可退款都是待结算状态)
    const COMMISSION_STATUS_SETTLED = 2; // 已结算
    const COMMISSION_STATUS_INVALID = 3; // 已失效
    const COMMISSION_STATUS_REFUND = 4; // 已退款


    /**
     * 获取佣金状态列表
     * @return array
     */
    public static function getCommissionStatusDict(): array
    {
        return [
            self::COMMISSION_STATUS_PENDING => '待付款',
            self::COMMISSION_STATUS_WAIT => '待结算',
            self::COMMISSION_STATUS_SETTLED => '已结算',
            self::COMMISSION_STATUS_INVALID => '已失效',
            self::COMMISSION_STATUS_REFUND => '已退款',
        ];
    }

    /**
     * 获取佣金状态描述
     * @param string $value
     * @return string
     */
    public static function getCommissionStatusDesc($value): string
    {
        $data = self::getCommissionStatusDict();
        return $data[$value] ?? '未知状态';
    }


    // 佣金类型
    const COMMISSION_TYPE_SELF = 'self'; // self
    const COMMISSION_TYPE_PARENT1 = 'parent1'; // 一级分销
    const COMMISSION_TYPE_PARENT2 = 'parent2'; // 二级分销


    /**
     * 获取佣金类型列表
     * @return array
     */
    public static function getCommissionTypeDict(): array
    {
        return [
            self::COMMISSION_TYPE_SELF => '自购',
            self::COMMISSION_TYPE_PARENT1 => '一级分销',
            self::COMMISSION_TYPE_PARENT2 => '二级分销',
        ];
    }

    /**
     * 获取佣金类型描述
     * @param string $value
     * @return string
     */
    public static function getCommissionTypeDesc($value): string
    {
        $data = self::getCommissionTypeDict();
        return $data[$value] ?? '未知类型';
    }



    

    
}
