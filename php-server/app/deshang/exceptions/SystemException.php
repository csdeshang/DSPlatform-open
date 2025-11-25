<?php

namespace app\deshang\exceptions;

/**
 * 系统异常
 * 
 * 用于处理系统层面的异常情况
 * 包括：
 * - 系统繁忙：分布式锁获取失败、系统负载过高、资源竞争激烈等
 * - 并发冲突：乐观锁版本冲突、并发更新冲突、数据版本不匹配等
 * - 系统资源：缓存连接失败、队列处理失败、第三方服务不可用等
 * 
 * HTTP 状态码: 503 Service Unavailable（默认）或 409 Conflict
 * 错误码: 50003（默认）或自定义错误码
 * 
 * 使用示例：
 * ```php
 * // 分布式锁获取失败（系统繁忙）
 * if (!CacheUtil::acquireLock($lockKey, 5)) {
 *     throw new SystemException('余额更新失败，系统繁忙，请稍后重试');
 * }
 * 
 * // 乐观锁重试失败（并发冲突）
 * if ($retryCount >= $maxRetries) {
 *     throw new SystemException('余额更新失败，版本冲突，已重试' . $maxRetries . '次');
 * }
 * 
 * // 系统资源不可用
 * if (!$cache->isConnected()) {
 *     throw new SystemException('缓存服务不可用，请稍后重试');
 * }
 * ```
 * 
 * @package app\deshang\exceptions
 */
class SystemException extends \RuntimeException
{
    /**
     * 构造函数
     * 
     * @param string $message 错误消息，如："系统繁忙，请稍后重试"、"版本冲突，已重试3次"
     * @param int $code HTTP状态码，默认 503（服务不可用），409（冲突）
     * @param \Throwable|null $previous 前置异常
     */
    public function __construct($message = "系统异常，请稍后重试", $code = 503, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

