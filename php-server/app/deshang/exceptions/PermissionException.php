<?php

namespace app\deshang\exceptions;

/**
 * 权限异常（权限不足）
 * 
 * 用于处理用户已登录但权限不足的情况
 * 包括：
 * - 操作权限不足：用户没有取消订单权限、店铺没有关闭订单权限等
 * - 资源权限不足：没有权限操作此店铺、没有权限访问此资源等
 * - 角色权限不足：角色不匹配、权限级别不够等
 * 
 * 注意：与 AuthException 的区别
 * - AuthException：认证失败（未登录、Token无效等）- HTTP 401
 * - PermissionException：权限不足（已登录但无权限）- HTTP 403
 * 
 * HTTP 状态码: 403 Forbidden（权限不足）
 * 错误码: 50004（默认）或自定义错误码
 * 
 * 使用示例：
 * ```php
 * // 操作权限不足
 * if (!in_array('cancel', $user_available_actions)) {
 *     throw new PermissionException('用户没有取消订单权限');
 * }
 * 
 * // 资源权限不足
 * if (!in_array($store_id, $user_store_list)) {
 *     throw new PermissionException('您没有权限操作此店铺');
 * }
 * 
 * // 角色权限不足
 * if ($user_role != 'admin') {
 *     throw new PermissionException('您没有管理员权限');
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class PermissionException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："用户没有取消订单权限"、"您没有权限操作此店铺"
     * @param int $code HTTP状态码，默认 403（权限不足）
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "权限不足", $code = 403, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

