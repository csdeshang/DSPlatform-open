<?php

namespace app\deshang\kv\services;

use think\facade\Cache;
use think\facade\Log;

/**
 * 限流服务类
 * 
 * 职责：限制高频访问（固定窗口计数）
 * 支持：Redis（Lua脚本原子操作）、File（文件锁保证原子性）
 */
class RateLimitService extends BaseKvService
{
    /**
     * 限流检查（原子操作）
     * 
     * @param string $key 限流键（如 'pay_rate_' . $user_id）
     * @param int $max 最大次数
     * @param int $window 窗口秒数
     * @return bool true=允许，false=超过限制
     */
    public function check(string $key, int $max = 1, int $window = 60): bool
    {
        if (!$this->isEnabled()) {
            return true;
        }

        if ($this->isRedis()) {
            return $this->redisRateLimit($key, $max, $window);
        } else {
            return $this->localRateLimit($key, $max, $window);
        }
    }

    /**
     * Redis 限流（Lua脚本原子操作）
     * 
     * @param string $key 限流键
     * @param int $max 最大次数
     * @param int $window 窗口秒数
     * @return bool true=允许，false=超过限制
     */
    private function redisRateLimit(string $key, int $max, int $window): bool
    {
        try {
            $cache = $this->getCacheInstance();
            /** @var \Redis|\Predis\Client|null $redis */
            $redis = $cache->handler();

            // Lua 脚本：原子递增、设置过期、判断是否超限
            $lua = "
                local current = redis.call('incr', KEYS[1])
                if current == 1 then
                    redis.call('expire', KEYS[1], ARGV[1])
                end
                return current <= tonumber(ARGV[2])
            ";

            if ($redis instanceof \Redis) {
                // Redis 扩展：eval($script, $args, $numKeys)
                $result = $redis->eval($lua, [$key, $window, $max], 1);
            } elseif ($redis instanceof \Predis\Client) {
                // Predis 扩展：eval($script, $numKeys, ...$keys, ...$args)
                $result = $redis->eval($lua, 1, $key, $window, $max);
            } else {
                return true; // 未知驱动，放行
            }

            return (bool)$result;
        } catch (\Exception $e) {
            Log::error("Redis限流检查失败 [{$key}]: " . $e->getMessage());
            return true; // 失败时放行（避免因限流服务故障导致业务中断）
        }
    }

    /**
     * 本地限流（文件锁保证原子性）
     * 
     * @param string $key 限流键
     * @param int $max 最大次数
     * @param int $window 窗口秒数
     * @return bool true=允许，false=超过限制
     */
    private function localRateLimit(string $key, int $max, int $window): bool
    {
        // 创建锁文件目录
        $lockDir = runtime_path('lock');
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }

        $fileLockPath = $lockDir . '/rate_limit_' . md5($key) . '.lock';
        $fp = @fopen($fileLockPath, 'w+');

        if (!$fp) {
            Log::error('限流文件锁打开失败: ' . $fileLockPath);
            return true; // 打开失败时放行
        }

        // 尝试获取非阻塞排他锁
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);
            return true; // 获取锁失败时放行（避免阻塞）
        }

        try {
            $cache = $this->getCacheInstance();
            $count = $cache->get($key);

            if ($count === false || $count === null) {
                // 首次访问，初始化为 1
                $count = 1;
                $cache->set($key, $count, $window);
            } else {
                // 递增计数
                $count = (int)$count + 1;
                if ($count > $max) {
                    return false; // 超过限制
                }
                $cache->set($key, $count, $window);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("本地限流检查失败 [{$key}]: " . $e->getMessage());
            return true; // 异常时放行
        } finally {
            // 确保释放文件锁
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }
}
