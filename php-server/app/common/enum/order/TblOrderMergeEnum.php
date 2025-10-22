<?php

namespace app\common\enum\order;


/**
 * 合并支付相关枚举
 */
class TblOrderMergeEnum
{

    // 合并支付状态
    const ORDER_MERGE_STATUS_PENDING = 0; // 待支付
    const ORDER_MERGE_STATUS_PAID = 1; // 已支付
    const ORDER_MERGE_STATUS_CLOSED = 2; // 已关闭


    // 获取所有合并支付状态列表
    public static function getAllOrderMergeStatusDict(): array
    {
        return [
            self::ORDER_MERGE_STATUS_PENDING => '待支付',
            self::ORDER_MERGE_STATUS_PAID => '已支付',
            self::ORDER_MERGE_STATUS_CLOSED => '已关闭',
        ];
    }

    // 获取合并支付状态描述
    public static function getOrderMergeStatusDesc($value): string
    {
        $data = self::getAllOrderMergeStatusDict();
        return $data[$value] ?? '未知合并支付状态';
    }



}