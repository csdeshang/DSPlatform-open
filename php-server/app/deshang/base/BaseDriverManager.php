<?php

namespace app\deshang\base;

use Exception;

abstract class BaseDriverManager
{

    protected $namespace; // 驱动类命名空间

    protected $driver; // 当前使用的驱动实例
    protected $driverName; // 当前使用的驱动名称
    protected $config; // 配置信息
    protected $drivers = []; // 缓存的驱动实例

    /**
     * 构造函数
     * @param string $driverName 驱动名称
     * @param array $config 驱动配置
     */
    public function __construct(string $driverName, array $config = [])
    {
        // 首字母大写处理
        $this->driverName = ucfirst(strtolower($driverName)) ?: $this->getDefaultDriverName();
        $this->config = $config;
        $this->loadDriver();
    }

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    abstract protected function getDefaultDriverName(): string;


    /**
     * 加载驱动
     */
    protected function loadDriver()
    {
        if (isset($this->drivers[$this->driverName])) {
            $this->driver = $this->drivers[$this->driverName];
            return;
        }

        if (empty($this->namespace)) {
            throw new Exception("命名空间未定义");
        }

        $driverClass = $this->namespace . '\\' . $this->driverName;

        if (!class_exists($driverClass)) {
            throw new Exception("驱动类 {$driverClass} 不存在");
        }
        $this->driver = new $driverClass($this->config);
        $this->drivers[$this->driverName] = $this->driver;
    }


    /**
     * 动态调用驱动方法
     * @param string $method 方法名
     * @param array $arguments 参数
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!method_exists($this->driver, $method)) {
            throw new Exception("方法 {$method} 不存在于驱动 {$this->driverName}");
        }
        // return call_user_func_array([$this->driver, $method], $arguments);
        return $this->driver->$method(...$arguments); // 使用 ... 语法
    }

    /**
     * 获取当前使用的驱动名称
     * @return string 当前使用的驱动名称
     */
    public function getDriverName(): string
    {
        return $this->driverName;
    }
}
