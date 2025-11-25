<?php

namespace app\deshang\exceptions;

/**
 * 状态异常（状态错误/不允许）
 * 
 * 用于处理状态机/工作流中状态不符合操作要求的情况
 * 包括：
 * - 订单状态错误：订单状态不是未付款，不能修改订单金额、订单状态错误等
 * - 退款状态不允许：当前退款状态不允许拒绝退款、不允许退货、不允许确认收货等
 * - 状态机限制：当前订单状态不允许处理退款、当前退款状态不允许此操作等
 * 
 * 注意：与 PermissionException 的区别
 * - PermissionException：通过 getAvailableActions 检查，状态限制已整合到权限列表中
 * - StateException：直接检查状态，状态不符合操作前置条件
 * 
 * HTTP 状态码: 200（业务错误，HTTP状态码正常）
 * 错误码: 50005（默认）或自定义错误码
 * 
 * 使用示例：
 * ```php
 * // 订单状态错误
 * if ($order_info['order_status'] != TblOrderEnum::ORDER_STATUS_PAID) {
 *     throw new StateException('订单状态错误');
 * }
 * 
 * // 订单状态不符合操作要求
 * if ($order_info['order_status'] !== TblOrderEnum::ORDER_STATUS_PENDING) {
 *     throw new StateException('订单状态不是未付款，不能修改订单金额');
 * }
 * 
 * // 退款状态不允许
 * if (!in_array($refund_status, [STATUS_PROCESSING, STATUS_FAILED])) {
 *     throw new StateException('当前退款状态不允许此操作');
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class StateException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："订单状态错误"、"当前退款状态不允许拒绝退款"
     * @param int $code 错误码，默认 0（使用系统默认错误码 50005）
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "状态错误", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

