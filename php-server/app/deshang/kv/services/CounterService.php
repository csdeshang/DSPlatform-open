<?php

namespace app\deshang\kv\services;

use think\facade\Cache;

/**
 * 计数器服务类
 * 
 * 职责：提供原子计数功能（递增/递减）
 * 支持：Redis（原子操作）、File（降级处理）
 */
class CounterService extends BaseKvService
{
    /**
     * 递增计数器
     * 
     * @param string $key 计数器键
     * @param int $value 递增量（默认 1）
     * @param string|null $store 存储名称（可选）
     * @return int|false 新值或失败时 false
     */
    public function increment(string $key, int $value = 1, ?string $store = null)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $cache = $this->getCacheInstance($store);
        return $cache->inc($key, $value);
    }

    /**
     * 递减计数器
     * 
     * @param string $key 计数器键
     * @param int $value 递减量（默认 1）
     * @param string|null $store 存储名称（可选）
     * @return int|false 新值或失败时 false
     */
    public function decrement(string $key, int $value = 1, ?string $store = null)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $cache = $this->getCacheInstance($store);
        return $cache->dec($key, $value);
    }

    /**
     * 获取计数器当前值
     * 
     * @param string $key 计数器键
     * @param int $default 默认值（默认 0）
     * @param string|null $store 存储名称（可选）
     * @return int 计数器当前值
     */
    public function get(string $key, $default = 0, ?string $store = null)
    {
        if (!$this->isEnabled()) {
            return $default;
        }
        $cache = $this->getCacheInstance($store);
        $value = $cache->get($key);
        return $value !== false && $value !== null ? (int)$value : $default;
    }

    /**
     * 设置计数器值
     * 
     * @param string $key 计数器键
     * @param int $value 要设置的值
     * @param int|null $ttl 生存时间（秒），null 表示永久
     * @param string|null $store 存储名称（可选）
     * @return bool 成功返回 true，失败返回 false
     */
    public function set(string $key, int $value, ?int $ttl = null, ?string $store = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $cache = $this->getCacheInstance($store);
        return $cache->set($key, $value, $ttl);
    }

    /**
     * 重置计数器（设置为 0）
     * 
     * @param string $key 计数器键
     * @param string|null $store 存储名称（可选）
     * @return bool 成功返回 true，失败返回 false
     */
    public function reset(string $key, ?string $store = null): bool
    {
        return $this->set($key, 0, null, $store);
    }
}
