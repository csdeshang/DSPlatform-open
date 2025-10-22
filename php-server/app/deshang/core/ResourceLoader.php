<?php

namespace app\deshang\core;

use think\facade\Config;
use think\facade\Cache;

class ResourceLoader
{
    /**
     * 加载配置文件（带缓存）
     *
     * @param string $name 配置文件名
     * @param bool $forceRefresh 是否强制刷新缓存
     * @return array
     */
    public static function loadConfig(string $name, bool $forceRefresh = false): array
    {
        $cacheKey = 'config_' . $name;
        
        if (!$forceRefresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $config = Config::load($name);
        Cache::set($cacheKey, $config, 3600); // 缓存1小时

        return $config;
    }

    /**
     * 自动加载类库
     *
     * @param string $namespace 命名空间前缀
     * @param string $directory 类库目录
     */
    public static function autoloadClasses(string $namespace, string $directory): void
    {
        spl_autoload_register(function ($class) use ($namespace, $directory) {
            if (strpos($class, $namespace) === 0) {
                $relativeClass = substr($class, strlen($namespace));
                $file = $directory . str_replace('\\', '/', $relativeClass) . '.php';
                
                if (file_exists($file)) {
                    require $file;
                }
            }
        });
    }

    /**
     * 加载资源文件（支持路径别名）
     *
     * @param string $path 资源路径（支持 @ 别名）
     * @return string
     * @throws \RuntimeException
     */
    public static function loadResource(string $path): string
    {
        // 解析路径别名
        if (strpos($path, '@') === 0) {
            $path = self::resolvePathAlias($path);
        }

        if (!file_exists($path)) {
            throw new \RuntimeException("Resource file not found: {$path}");
        }

        return file_get_contents($path);
    }

    /**
     * 解析路径别名
     *
     * @param string $alias 路径别名（如 @app）
     * @return string
     */
    protected static function resolvePathAlias(string $alias): string
    {
        $aliases = [
            '@app' => app_path(),
            '@config' => config_path(),
            '@public' => public_path(),
            '@runtime' => runtime_path(),
        ];

        foreach ($aliases as $prefix => $basePath) {
            if (strpos($alias, $prefix) === 0) {
                return $basePath . substr($alias, strlen($prefix));
            }
        }

        return $alias;
    }

    /**
     * 加载视图文件
     *
     * @param string $view 视图名称
     * @param array $data 视图数据
     * @return string
     */
    public static function loadView(string $view, array $data = []): string
    {
        return view($view, $data)->getContent();
    }
}