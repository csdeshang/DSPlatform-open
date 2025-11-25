<?php

namespace app\deshang\exceptions;

/**
 * 通用业务异常
 * 
 * 用于处理业务逻辑相关的异常
 * 包括：
 * - 业务规则违反：积分不足、余额不足、订单状态错误等
 * - 重复操作：已收藏、已绑定、支付处理中等
 * - 参数错误：参数缺失、参数格式错误、配置错误等
 * 
 * HTTP 状态码: 200（业务错误，HTTP状态码正常）
 * 错误码: 50001（默认）或自定义错误码
 * 
 * 使用示例：
 * ```php
 * // 业务规则违反
 * if ($user_points < $need_points) {
 *     throw new CommonException('积分不足');
 * }
 * 
 * // 重复操作
 * if ($is_favorited) {
 *     throw new CommonException('已收藏该店铺');
 * }
 * 
 * // 参数错误
 * if (empty($store_id)) {
 *     throw new CommonException('参数缺失或无效: store_id');
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class CommonException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："积分不足"、"已收藏该店铺"、"支付渠道错误"
     * @param int $code 错误码，默认 0（使用系统默认错误码 50001）
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
