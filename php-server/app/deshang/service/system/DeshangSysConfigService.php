<?php

namespace app\deshang\service\system;

use app\deshang\exceptions\CommonException;

use app\deshang\service\BaseDeshangService;

use app\common\dao\system\SysConfigDao;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;



class DeshangSysConfigService extends BaseDeshangService
{



    /**
     * 获取单个配置项
     * @param string $key 配置键，格式为$type:$key
     * @param bool $is_cache 是否使用缓存，默认true。设置为false将跳过缓存直接查询数据库
     * @return mixed|null 返回配置值，不存在返回null
     * @throws CommonException 当配置键为空或格式不正确时抛出异常
     *
     * 示例：
     * $config = $service->getSysConfigByKey('system:site_name');
     *
     * 流程说明：
     * 1. 解析$type和$key
     * 2. 从缓存获取配置
     * 3. 缓存不存在则从数据库获取
     * 4. 将数据库结果存入缓存
     * 5. 返回指定key的配置值
     */
    public function getSysConfigByKey($key, $is_cache = true)
    {

        $is_cache = false;
        
        if (empty($key)) {
            throw new CommonException('配置键不能为空');
        }

        // 验证格式是否为 type:key
        if (strpos($key, ':') === false) {
            throw new CommonException('配置键格式不正确，必须为 type:key 的形式');
        }

        list($type, $key) = explode(':', $key, 2);

        // 生成缓存key
        $cacheKey = sprintf(CacheKeyManager::SYS_CONFIG_KEY, $type);

        // 从缓存获取
        $allConfigs = $is_cache ? KvManager::cache()->get($cacheKey) : null;

        if (empty($allConfigs)) {
            // 缓存不存在或禁用缓存，从数据库获取
            $condition = ['config_type' => $type];
            $allConfigs = (new SysConfigDao())->getSysConfigList($condition);

            if (empty($allConfigs)) {
                throw new CommonException('配置类型不存在：' . $type);
            }

            // 将配置项转换为键值对
            $allConfigs = array_column($allConfigs, 'config_value', 'config_key');

            // 存入缓存
            if ($is_cache) {
                KvManager::cache()->set($cacheKey, $allConfigs, 3600); // 缓存1小时
            }
        }

        if (!array_key_exists($key, $allConfigs)) {
            throw new CommonException('配置项不存在：' . $key);
        }

        return $allConfigs[$key];
    }

    /**
     * 获取多个配置项
     * @param array $keys 配置键数组，每个键格式为$type:$key
     * @param bool $is_cache 是否使用缓存，默认true
     * @return array 返回配置值数组
     * @throws CommonException 当配置键格式不正确时抛出异常
     */
    public function getSysConfigByKeys(array $keys, $is_cache = true)
    {
        if (empty($keys)) {
            return [];
        }

        $result = [];
        $groupedKeys = [];

        // 按类型分组配置键
        foreach ($keys as $fullKey) {
            if (strpos($fullKey, ':') === false) {
                throw new CommonException('配置键格式不正确，必须为 type:key 的形式');
            }

            list($type, $key) = explode(':', $fullKey, 2);
            $groupedKeys[$type][] = $key;
        }

        // 按类型获取配置
        foreach ($groupedKeys as $type => $typeKeys) {
            $typeConfigs = $this->getSysConfigByType($type, $is_cache);
            
            foreach ($typeKeys as $key) {
                if (isset($typeConfigs[$key])) {
                    $result[$type.':'.$key] = $typeConfigs[$key];
                }
            }
        }

        return $result;
    }

    /**
     * 获取指定类型的所有配置
     * @param string $type 配置类型
     * @param bool $is_cache 是否使用缓存，默认true
     * @return array 返回配置值数组
     * @throws CommonException 当配置类型不存在时抛出异常
     */
    public function getSysConfigByType($type, $is_cache = true)
    {
        if (empty($type)) {
            throw new CommonException('配置类型不能为空');
        }

        // 生成缓存key
        $cacheKey = sprintf(CacheKeyManager::SYS_CONFIG_KEY, $type);

        // 从缓存获取
        $allConfigs = $is_cache ? KvManager::cache()->get($cacheKey) : null;

        if (empty($allConfigs)) {
            // 缓存不存在或禁用缓存，从数据库获取
            $condition = ['config_type' => $type];
            $configList = (new SysConfigDao())->getSysConfigList($condition);

            if (empty($configList)) {
                throw new CommonException('配置类型不存在：' . $type);
            }

            // 将配置项转换为键值对
            $allConfigs = array_column($configList, 'config_value', 'config_key');

            // 存入缓存
            if ($is_cache) {
                KvManager::cache()->set($cacheKey, $allConfigs, 3600); // 缓存1小时
            }
        }

        return $allConfigs;
    }

    /**
     * 获取多个类型的所有配置
     * @param array $types 配置类型数组
     * @param bool $is_cache 是否使用缓存，默认true
     * @return array 返回配置值数组，按类型分组
     */
    public function getSysConfigByTypes(array $types, $is_cache = true)
    {
        if (empty($types)) {
            return [];
        }

        $result = [];

        foreach ($types as $type) {
            try {
                $result[$type] = $this->getSysConfigByType($type, $is_cache);
            } catch (CommonException $e) {
                // 记录错误但继续处理其他类型
                // 可以根据需要决定是否将错误信息添加到结果中
                $result[$type] = [];
            }
        }

        return $result;
    }

   
    

    
  
}
