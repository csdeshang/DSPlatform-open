<?php

namespace app\common\enum\trade;


/**
 * 退款相关
 */
class TradeRefundEnum
{


    // 来源类型 (不同业务的支付来源，涉及的表不一样)
    const SOURCE_TYPE_REFUND = 'refund'; // 退款

    // 来源类型
    public static function getSourceTypeDict(): array
    {
        return [
            self::SOURCE_TYPE_REFUND => '退款',
        ];
    }
    // 获取来源类型描述
    public static function getSourceTypeDesc($value): string
    {
        $data = self::getSourceTypeDict();
        return $data[$value] ?? '未知来源';
    }



    // 退款状态
    const REFUND_STATUS_PENDING = 0; // 待退款
    const REFUND_STATUS_SUCCESS = 1; // 退款成功
    const REFUND_STATUS_FAILED = 2; // 退款失败

    // 退款状态
    public static function getRefundStatusDict(): array
    {
        return [
            self::REFUND_STATUS_PENDING => '待退款',
            self::REFUND_STATUS_SUCCESS => '退款成功',
            self::REFUND_STATUS_FAILED => '退款失败',
        ];
    }
    // 获取退款状态描述
    public static function getRefundStatusDesc($value): string
    {
        $data = self::getRefundStatusDict();
        return $data[$value] ?? '未知状态';
    }









}
