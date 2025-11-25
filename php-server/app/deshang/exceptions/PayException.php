<?php

namespace app\deshang\exceptions;

/**
 * 支付异常
 * 
 * 用于处理支付相关的异常情况
 * 包括：
 * - 支付失败：支付宝支付失败、微信支付失败、银行卡支付失败等
 * - 支付渠道错误：支付渠道不存在、支付渠道配置错误等
 * - 支付超时：支付请求超时、支付回调超时等
 * - 支付金额错误：支付金额不匹配、支付金额为0等
 * - 支付状态异常：订单已支付、订单已取消、订单状态错误等
 * 
 * HTTP 状态码: 200（业务错误，HTTP状态码正常）
 * 错误码: 50002（默认）或自定义错误码
 * 
 * 使用示例：
 * ```php
 * // 支付失败
 * try {
 *     $result = $pay->pay($order);
 * } catch (\Exception $e) {
 *     throw new PayException('支付宝支付失败: ' . $e->getMessage());
 * }
 * 
 * // 支付渠道错误
 * if (!in_array($pay_type, ['alipay', 'wechat'])) {
 *     throw new PayException('支付渠道不存在');
 * }
 * 
 * // 支付金额错误
 * if ($pay_amount <= 0) {
 *     throw new PayException('支付金额必须大于0');
 * }
 * 
 * // 支付状态异常
 * if ($order_status != 'unpaid') {
 *     throw new PayException('订单状态错误，无法支付');
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class PayException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："支付宝支付失败"、"支付渠道不存在"、"支付金额错误"
     * @param int $code 错误码，默认 0（使用系统默认错误码 50002）
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "支付失败", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

