<?php

namespace app\deshang\exceptions;

/**
 * 认证/授权异常
 * 
 * 用于处理用户身份验证和权限验证失败的情况
 * 包括：
 * - 认证失败：Token 无效、登录过期、未登录等（HTTP 401）
 * - 权限不足：角色不匹配、账户被禁用、无权限访问等（HTTP 403）
 * 
 * HTTP 状态码: 401 或 403
 * 默认状态码: 403（权限不足）
 * 
 * 使用示例：
 * ```php
 * // 未登录（默认 403）
 * if (!$this->user_id) {
 *     throw new AuthException('请先登录');
 * }
 * 
 * // 账户被禁用（默认 403）
 * if (!$this->user_is_enabled) {
 *     throw new AuthException('您的账户已被禁用');
 * }
 * 
 * // Token 过期（明确指定 401）
 * if ($token_expired) {
 *     throw new AuthException('Token 已过期，请重新登录', 401);
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class AuthException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："请先登录"、"您没有权限操作此店铺"
     * @param int $code HTTP状态码，默认 403（权限不足），401（认证失败）
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "认证失败，请先登录", $code = 403, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
