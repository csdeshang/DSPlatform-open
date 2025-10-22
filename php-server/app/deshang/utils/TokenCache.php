<?php

namespace app\deshang\utils;

/**
 * Token缓存管理类
 * 负责Token版本控制和失效管理
 */
class TokenCache
{
    /**
     * Token版本缓存键前缀
     */
    const TOKEN_VERSION_PREFIX = 'token_version_';
    
    /**
     * 默认缓存过期时间（7天）
     */
    const DEFAULT_EXPIRE = 3600 * 24 * 7;

    /**
     * 验证Token版本是否有效
     * 
     * @param string $role 角色类型
     * @param int $user_id 用户ID
     * @param int $token_version Token中的版本号
     * @return bool
     */
    public function validateTokenVersion(string $role, int $user_id, int $token_version): bool
    {
        // 检查全局版本号
        $cache_key = self::TOKEN_VERSION_PREFIX . "{$role}_{$user_id}";
        $current_version = CacheUtil::get($cache_key, 1);
        
        return $current_version == $token_version;
    }

    /**
     * 使所有Token失效（递增版本号）
     * 
     * @param string $role 角色类型
     * @param int $user_id 用户ID
     * @return void
     */
    public function invalidateToken(string $role, int $user_id): void
    {
        $cache_key = self::TOKEN_VERSION_PREFIX . "{$role}_{$user_id}";
        
        // 获取当前版本号
        $current_version = CacheUtil::get($cache_key, 1);
        
        // 版本号+1
        $new_version = $current_version + 1;
        
        // 设置新版本号
        CacheUtil::set($cache_key, $new_version, self::DEFAULT_EXPIRE);
    }

    /**
     * 获取当前Token版本号
     * 
     * @param string $role 角色类型
     * @param int $user_id 用户ID
     * @return int
     */
    public function getCurrentVersion(string $role, int $user_id): int
    {
        $cache_key = self::TOKEN_VERSION_PREFIX . "{$role}_{$user_id}";
        return CacheUtil::get($cache_key, 1);
    }
}
