<?php

namespace app\deshang\exceptions;

/**
 * 数据不存在异常
 * 
 * 用于处理数据查询结果为空的场景
 * 包括：订单不存在、用户不存在、商品不存在、退款不存在等
 * 
 * HTTP 状态码: 404
 * 错误码: 40401
 * 
 * 使用示例：
 * ```php
 * if (!$order_info) {
 *     throw new NotFoundException('订单不存在');
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class NotFoundException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："订单不存在"、"用户不存在"
     * @param int $code 错误码，默认 40401
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "数据不存在", $code = 40401, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
