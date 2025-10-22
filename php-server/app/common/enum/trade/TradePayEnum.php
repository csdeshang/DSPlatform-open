<?php

namespace app\common\enum\trade;


/**
 * 支付相关(主要针对订单支付)
 */
class TradePayEnum
{
    // 来源类型 (不同业务的支付来源，涉及的表不一样)
    const SOURCE_TYPE_RECHARGE = 'recharge'; // 充值
    const SOURCE_TYPE_ORDER = 'order'; // 单个店铺订单  
    const SOURCE_TYPE_ORDER_MERGE = 'order_merge'; // 合并支付订单(多个店铺一起支付)



    // 来源类型
    public static function getSourceTypeDict(): array
    {
        return [
            self::SOURCE_TYPE_RECHARGE => '充值',
            self::SOURCE_TYPE_ORDER => '订单',
            self::SOURCE_TYPE_ORDER_MERGE => '订单合并',
        ];
    }
    // 获取来源类型描述
    public static function getSourceTypeDesc($value): string
    {
        $data = self::getSourceTypeDict();
        return $data[$value] ?? '未知来源';
    }


    // 支付状态
    const PAY_STATUS_PENDING = 0; // 待支付
    const PAY_STATUS_SUCCESS = 1; // 成功
    const PAY_STATUS_CLOSED = 2; // 关闭


    // 获取支付状态列表
    public static function getPayStatusDict(): array
    {
        return [
            self::PAY_STATUS_PENDING => '待支付',
            self::PAY_STATUS_SUCCESS => '成功',
            self::PAY_STATUS_CLOSED => '关闭',  
        ];
    }
    // 获取支付状态描述
    public static function getPayStatusDesc($value): string
    {
        $data = self::getPayStatusDict();
        return $data[$value] ?? '未知状态';
    }


}