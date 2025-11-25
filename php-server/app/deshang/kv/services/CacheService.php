<?php

namespace app\deshang\kv\services;

use think\facade\Cache;
use think\facade\Log;

/**
 * 缓存服务类
 * 
 * 职责：纯缓存操作（set/get/delete/clear）
 * 不包含任何键常量，键常量统一由 CacheKeyManager 管理
 * 支持多种驱动：File、Redis等
 */
class CacheService extends BaseKvService
{
    /**
     * 设置缓存值，支持可选的 TTL
     *
     * @param string $key 缓存键
     * @param mixed $value 要缓存的值
     * @param int|null $ttl 生存时间（秒），null 表示永久
     * @param string|array|null $tag 缓存标签（可选）
     * @param string|null $store 缓存存储名称（可选）
     * @return bool
     */
    public function set(string $key, $value, ?int $ttl = null, $tag = null, ?string $store = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $cache = $this->getCacheInstance($store);
        if ($tag) {
            $cache = $cache->tag($tag);
        }
        return $cache->set($key, $value, $ttl);
    }

    /**
     * 获取缓存值
     *
     * @param string $key 缓存键
     * @param mixed $default 如果键不存在的默认值
     * @param string|null $store 缓存存储名称（可选）
     * @return mixed
     */
    public function get(string $key, $default = null, ?string $store = null)
    {
        if (!$this->isEnabled()) {
            return $default;
        }
        $cache = $this->getCacheInstance($store);
        return $cache->get($key, $default);
    }

    /**
     * 删除缓存条目
     *
     * @param string $key 缓存键
     * @param string|null $store 缓存存储名称（可选）
     * @return bool
     */
    public function delete(string $key, ?string $store = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $cache = $this->getCacheInstance($store);
        return $cache->delete($key);
    }

    /**
     * 清除所有缓存或指定标签的缓存（谨慎使用）
     *
     * @param string|array|null $tag 要清除的标签（可选）
     * @param string|null $store 缓存存储名称（可选）
     * @return bool
     */
    public function clear($tag = null, ?string $store = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $cache = $this->getCacheInstance($store);
        if ($tag) {
            return $cache->tag($tag)->clear();
        }
        return $cache->clear();
    }

    /**
     * 记住缓存，如果不存在则从回调获取
     * 
     * @param string $key 缓存键
     * @param callable $callback 回调函数，用于获取缓存值
     * @param int|null $ttl 生存时间（秒），null 表示永久
     * @param string|array|null $tag 缓存标签（可选）
     * @param string|null $store 缓存存储名称（可选）
     * @return mixed
     */
    public function remember(string $key, callable $callback, ?int $ttl = null, $tag = null, ?string $store = null)
    {
        try {
            $value = $this->get($key, null, $store);
            if (!is_null($value)) {
                return $value;
            }
            
            $value = $callback();
            $this->set($key, $value, $ttl, $tag, $store);
            
            return $value;
        } catch (\Exception $e) {
            Log::error("缓存记住失败 [{$key}]: " . $e->getMessage());
            // 降级处理：直接执行回调返回结果
            return $callback();
        }
    }

    /**
     * 获取并删除缓存
     * 
     * @param string $key 缓存键
     * @param mixed $default 如果键不存在的默认值
     * @param string|null $store 缓存存储名称（可选）
     * @return mixed
     */
    public function pull(string $key, $default = null, ?string $store = null)
    {
        try {
            $value = $this->get($key, $default, $store);
            $this->delete($key, $store);
            return $value;
        } catch (\Exception $e) {
            Log::error("缓存拉取失败 [{$key}]: " . $e->getMessage());
            return $default;
        }
    }

    /**
     * 批量获取缓存
     * 
     * @param array $keys 缓存键数组
     * @param mixed $default 如果键不存在的默认值
     * @param string|null $store 缓存存储名称（可选）
     * @return array 返回键值对数组
     */
    public function multiple(array $keys, $default = null, ?string $store = null): array
    {
        try {
            $result = [];
            foreach ($keys as $key) {
                $result[$key] = $this->get($key, $default, $store);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error("缓存批量获取失败: " . $e->getMessage());
            // 降级处理：返回所有键的默认值
            return array_fill_keys($keys, $default);
        }
    }
}
