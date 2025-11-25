<?php

namespace app\deshang\kv\services;

use think\facade\Cache;

/**
 * KV 服务基类
 * 
 * 提供所有 KV 服务的公共方法
 */
abstract class BaseKvService
{
    /**
     * 检查缓存是否启用
     * 
     * @return bool
     */
    protected function isEnabled(): bool
    {
        return env('CACHE_ENABLED', true);
    }

    /**
     * 检查是否为 Redis 驱动
     * 
     * @return bool
     */
    protected function isRedis(): bool
    {
        // 使用系统配置方式检查
        return Cache::getConfig('type') === 'redis';
    }

    /**
     * 获取缓存实例（支持指定存储）
     * 
     * @param string|null $store 存储名称（可选）
     * @return mixed Cache 实例
     */
    protected function getCacheInstance(?string $store = null)
    {
        return $store ? Cache::store($store) : Cache::instance();
    }
}

