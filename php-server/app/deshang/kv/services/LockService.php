<?php

namespace app\deshang\kv\services;

use think\facade\Cache;
use think\facade\Log;

/**
 * 分布式锁服务类
 * 
 * 职责：提供分布式锁功能（支持多种驱动）
 * 支持：
 * - Redis（原子操作，跨机器）
 * - File（文件锁保证单机原子性，不能跨机器）
 */
class LockService extends BaseKvService
{
    /**
     * 存储文件句柄信息（key => handle info）
     * 用于文件锁场景，在释放时关闭文件句柄
     * 结构：['file' => resource, 'created_at' => int, 'expire_at' => int]
     */
    private static array $fileHandles = [];

    /**
     * 获取分布式锁
     * 
     * @param string $lockKey 锁键
     * @param int $expire 过期时间（秒）
     * @return string|false 成功返回锁值（用于释放），失败返回 false
     */
    public function acquire(string $lockKey, int $expire = 10)
    {
        if (!$this->isEnabled()) {
            // 缓存未启用，返回特殊标识（开发环境）
            return 'disabled_' . uniqid('', true);
        }

        $cache = $this->getCacheInstance();

        // Redis 环境：使用原子操作
        if ($this->isRedis()) {
            return $this->acquireRedisLock($cache, $lockKey, $expire);
        } else {
            // 本地环境：使用文件锁保证原子性
            return $this->acquireLocalLock($cache, $lockKey, $expire);
        }
    }

    /**
     * 释放分布式锁（安全释放，验证锁值）
     * 
     * @param string|false $lockValue 锁值（acquire 返回的值）
     * @param string $lockKey 锁键
     * @return void
     */
    public function release($lockValue, string $lockKey): void
    {
        if (!$this->isEnabled() || empty($lockKey)) {
            return;
        }

        $cache = $this->getCacheInstance();

        // 如果 lockValue 是 false，说明获取锁失败，无需释放
        if ($lockValue === false) {
            return;
        }

        // 如果 lockValue 是 'disabled_' 开头的特殊标识，说明缓存未启用，直接删除
        if (is_string($lockValue) && strpos($lockValue, 'disabled_') === 0) {
            $cache->delete($lockKey);
            return;
        }

        // Redis 环境：使用 Lua 脚本安全释放
        if ($this->isRedis()) {
            $this->releaseRedisLock($cache, $lockValue, $lockKey);
        } else {
            // 本地环境：释放文件锁
            $this->releaseLocalLock($cache, $lockValue, $lockKey);
        }
    }

    /**
     * 获取 Redis 锁（原子操作）
     * 
     * @param mixed $cache Cache 实例
     * @param string $lockKey 锁键
     * @param int $expire 过期时间（秒）
     * @return string|false 成功返回锁值，失败返回 false
     */
    private function acquireRedisLock($cache, string $lockKey, int $expire)
    {
        try {
            // 获取 Redis 原生连接
            /** @var \Redis|\Predis\Client|null $redis */
            $redis = $cache->handler();

            // 生成唯一锁值（时间戳 + 微秒 + 进程ID，确保唯一性）
            $lockValue = uniqid('', true) . '_' . getmypid();

            // 使用 Redis 原生命令 SET NX EX（原子操作）
            if ($redis instanceof \Redis) {
                // Redis 扩展：使用数组参数格式
                $result = $redis->set($lockKey, $lockValue, ['nx', 'ex' => $expire]);
            } elseif ($redis instanceof \Predis\Client) {
                // Predis 扩展：使用字符串参数格式
                $result = $redis->set($lockKey, $lockValue, 'EX', $expire, 'NX');
            } else {
                // 未知的 Redis 客户端类型
                return false;
            }

            return $result ? $lockValue : false;
        } catch (\Exception $e) {
            // Redis 连接异常，记录日志但不抛出异常（降级处理）
            Log::error('Redis 锁获取失败: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 释放 Redis 锁（使用 Lua 脚本，安全释放）
     * 
     * @param mixed $cache Cache 实例
     * @param string $lockValue 锁值
     * @param string $lockKey 锁键
     * @return void
     */
    private function releaseRedisLock($cache, string $lockValue, string $lockKey): void
    {
        try {
            /** @var \Redis|\Predis\Client|null $redis */
            $redis = $cache->handler();

            // Lua 脚本：原子验证并删除（防止误删其他进程的锁）
            $lua = "
                if redis.call('get', KEYS[1]) == ARGV[1] then
                    return redis.call('del', KEYS[1])
                else
                    return 0
                end
            ";

            if ($redis instanceof \Redis) {
                // Redis 扩展：eval($script, $args, $numKeys)
                $redis->eval($lua, [$lockKey, $lockValue], 1);
            } elseif ($redis instanceof \Predis\Client) {
                // Predis 扩展：eval($script, $numKeys, ...$keys, ...$args)
                $redis->eval($lua, 1, $lockKey, $lockValue);
            }
        } catch (\Exception $e) {
            // Redis 连接异常，记录日志但不抛出异常
            Log::error('Redis 锁释放失败: ' . $e->getMessage());
        }
    }

    /**
     * 获取本地锁（使用文件锁保证原子性）
     * 
     * 使用 flock() 文件锁保护临界区，确保 get() 和 set() 操作的原子性
     * 注意：文件锁只能保护单机内的并发，不能跨机器
     * 
     * @param mixed $cache Cache 实例
     * @param string $lockKey 锁键
     * @param int $expire 过期时间（秒）
     * @return string|false 成功返回锁值字符串，失败返回 false
     */
    private function acquireLocalLock($cache, string $lockKey, int $expire): string|false
    {
        // 定期清理过期的文件句柄（超过50个时触发，避免频繁清理）
        if (count(self::$fileHandles) > 50) {
            $this->cleanupFileHandles($cache);
        }

        // 创建锁文件目录
        $lockDir = runtime_path('lock');
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }

        // 锁文件路径（使用 MD5 避免特殊字符问题）
        $fileLockPath = $lockDir . '/lock_' . md5($lockKey) . '.lock';
        
        // 打开锁文件（如果不存在则创建）
        $fp = @fopen($fileLockPath, 'w+');
        if (!$fp) {
            Log::error('文件锁打开失败: ' . $fileLockPath);
            return false;
        }

        // 尝试获取非阻塞排他锁（LOCK_EX | LOCK_NB）
        // LOCK_EX: 排他锁（写入锁）
        // LOCK_NB: 非阻塞模式，如果无法立即获取锁则返回 false
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);
            return false; // 锁被其他进程占用
        }

        try {
            // 在文件锁保护下执行操作（临界区）
            $value = $cache->get($lockKey);
            
            // 锁存在，检查是否过期
            if ($value) {
                $expireTimestamp = null;
                if (is_string($value)) {
                    // 新格式：包含锁值和时间戳 "lockValue_expireTimestamp"
                    if (strpos($value, '_') !== false) {
                        $parts = explode('_', $value);
                        $lastPart = end($parts);
                        if (is_numeric($lastPart) && $lastPart > time()) {
                            $expireTimestamp = (int)$lastPart;
                        }
                    } elseif (is_numeric($value) && $value > time()) {
                        // 旧格式：直接是过期时间戳
                        $expireTimestamp = (int)$value;
                    }
                } elseif (is_numeric($value) && $value > time()) {
                    $expireTimestamp = (int)$value;
                }
                
                // 锁存在且未过期
                if ($expireTimestamp !== null && $expireTimestamp > time()) {
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    return false;
                }
                
                // 锁已过期，删除旧锁
                $cache->delete($lockKey);
            }
            
            // 生成唯一锁值
            $lockValue = 'local_' . uniqid('', true) . '_' . getmypid();
            
            // 设置新锁（存储锁值和过期时间戳）
            $expireTimestamp = time() + $expire;
            $cache->set($lockKey, $lockValue . '_' . $expireTimestamp, $expire);
            
            // 存储文件句柄信息（数组结构，包含更多信息用于清理）
            self::$fileHandles[$lockKey] = [
                'file' => $fp,
                'created_at' => time(),
                'expire_at' => $expireTimestamp
            ];
            
            // 只返回锁值字符串（统一接口）
            return $lockValue;
            
        } catch (\Exception $e) {
            // 异常时释放文件锁
            flock($fp, LOCK_UN);
            fclose($fp);
            Log::error('本地锁获取失败: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 清理过期的文件句柄（安全清理）
     * 
     * 只清理真正过期的锁（通过检查缓存值确认）
     * 
     * @param mixed $cache Cache 实例
     * @return void
     */
    private function cleanupFileHandles($cache): void
    {
        $now = time();
        
        foreach (self::$fileHandles as $lockKey => $handleInfo) {
            // 检查锁是否真的过期（通过缓存值）
            $cacheValue = $cache->get($lockKey);
            $isExpired = false;
            
            if ($cacheValue === false || $cacheValue === null) {
                // 缓存中不存在，说明锁已释放或过期
                $isExpired = true;
            } elseif (is_string($cacheValue)) {
                // 解析过期时间戳
                if (strpos($cacheValue, '_') !== false) {
                    $parts = explode('_', $cacheValue);
                    $lastPart = end($parts);
                    if (is_numeric($lastPart)) {
                        $expireTimestamp = (int)$lastPart;
                        $isExpired = $expireTimestamp <= $now;
                    }
                }
            }
            
            // 如果锁已过期，清理文件句柄
            if ($isExpired) {
                if (isset($handleInfo['file']) && is_resource($handleInfo['file'])) {
                    try {
                        @flock($handleInfo['file'], LOCK_UN);
                        @fclose($handleInfo['file']);
                    } catch (\Exception $e) {
                        // 忽略清理错误，避免影响主流程
                    }
                }
                unset(self::$fileHandles[$lockKey]);
            }
        }
    }

    /**
     * 释放本地文件锁
     * 
     * @param mixed $cache Cache 实例
     * @param string $lockValue 锁值
     * @param string $lockKey 锁键
     * @return void
     */
    private function releaseLocalLock($cache, string $lockValue, string $lockKey): void
    {
        // 从静态变量中获取文件句柄
        if (!isset(self::$fileHandles[$lockKey])) {
            // 文件句柄不存在，只删除缓存中的锁
            $cache->delete($lockKey);
            return;
        }

        $handleInfo = self::$fileHandles[$lockKey];
        unset(self::$fileHandles[$lockKey]);
        
        $fp = $handleInfo['file'] ?? null;

        if ($fp && is_resource($fp)) {
            try {
                // 释放文件锁
                flock($fp, LOCK_UN);
                
                // 关闭文件句柄
                fclose($fp);
            } catch (\Exception $e) {
                Log::error('文件锁释放失败: ' . $e->getMessage());
            }
        }
        
        // 删除缓存中的锁（确保资源清理）
        $cache->delete($lockKey);
    }
}
