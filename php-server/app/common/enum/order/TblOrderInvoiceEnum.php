<?php

namespace app\common\enum\order;

/**
 * 订单开票状态（全局 0 起连续编号）
 *
 * tbl_order.invoice_status 与 tbl_order_invoice.invoice_status 均使用本类常量。
 * 0=未申请（仅订单在尚无申请时）；申请单从 1 起进入流程。
 */
class TblOrderInvoiceEnum
{
    /** 尚未提交开票申请（仅订单表） */
    public const STATUS_NOT_SUBMITTED = 0;

    public const STATUS_PENDING = 1;

    public const STATUS_PROCESSING = 2;

    public const STATUS_ISSUED = 3;

    public const STATUS_REJECTED = 4;

    public const STATUS_VOIDED = 5;

    /**
     * 占用「进行中」名额、禁止重复提交申请的状态
     *
     * @return int[]
     */
    public static function blockingInvoiceStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_ISSUED,
        ];
    }

    public static function getAllInvoiceStatusDict(): array
    {
        return [
            self::STATUS_NOT_SUBMITTED => '未申请',
            self::STATUS_PENDING => '待处理',
            self::STATUS_PROCESSING => '处理中',
            self::STATUS_ISSUED => '已开票',
            self::STATUS_REJECTED => '已驳回',
            self::STATUS_VOIDED => '已作废',
        ];
    }

    public static function getInvoiceStatusDesc($value): string
    {
        $dict = self::getAllInvoiceStatusDict();
        return $dict[$value] ?? '未知状态';
    }
}
