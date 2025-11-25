<?php

namespace app\deshang\kv;

use app\deshang\kv\services\CacheService;
use app\deshang\kv\services\LockService;
use app\deshang\kv\services\CounterService;
use app\deshang\kv\services\RateLimitService;

/**
 * 键值存储管理器（统一入口）
 * 
 * 提供基于键值存储的多功能工具，支持多种驱动（File、Redis等）
 * 
 * 功能模块：
 * - cache(): 缓存操作（set/get/delete/clear）
 * - lock(): 分布式锁（acquire/release）
 * - counter(): 计数器（increment/decrement）
 * - rateLimit(): 限流（check）
 * 
 * 使用示例：
 * ```php
 * use app\deshang\kv\KvManager;
 * use app\deshang\kv\keys\CacheKeyManager;
 * use app\deshang\kv\keys\LockKeyManager;
 * 
 * // 缓存操作
 * $cacheKey = sprintf(CacheKeyManager::GOODS_INFO_KEY, $goods_id);
 * KvManager::cache()->set($cacheKey, $goodsInfo, 3600);
 * 
 * // 分布式锁
 * $lockKey = sprintf(LockKeyManager::LOCK_USER_BALANCE_KEY, $user_id);
 * $lockValue = KvManager::lock()->acquire($lockKey, 10);
 * if ($lockValue) {
 *     try {
 *         // 业务逻辑
 *     } finally {
 *         KvManager::lock()->release($lockValue, $lockKey);
 *     }
 * }
 * ```
 */
class KvManager
{
    /**
     * 缓存服务
     * @return CacheService
     */
    public static function cache(): CacheService
    {
        return new CacheService();
    }

    /**
     * 分布式锁服务
     * @return LockService
     */
    public static function lock(): LockService
    {
        return new LockService();
    }

    /**
     * 计数器服务
     * @return CounterService
     */
    public static function counter(): CounterService
    {
        return new CounterService();
    }

    /**
     * 限流服务
     * @return RateLimitService
     */
    public static function rateLimit(): RateLimitService
    {
        return new RateLimitService();
    }
}

