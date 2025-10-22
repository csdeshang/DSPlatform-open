<?php

namespace app\deshang\core;

use think\facade\App;
use app\deshang\exceptions\CommonException;

use app\common\dao\system\SysPlatformDao;



/**
 * 主要用于平台的加载
 */
class PlatformLoader
{
    public static function loadRoute(string $name) {
        if(!in_array($name, ['adminapi', 'api', 'storeapi'])) {
            throw new CommonException("{$name} 不存在");
        }

        // 获取平台列表
        $platform_list = (new SysPlatformDao())->getSysPlatformList([['platform', '!=', 'system']]);
        
        foreach ($platform_list as $k => $v) {
            $route_path = root_path() . 'app' . DIRECTORY_SEPARATOR . 'platform' . DIRECTORY_SEPARATOR . $v['platform'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "route" . DIRECTORY_SEPARATOR . "route.php";
            if (is_file($route_path)) {
                include $route_path;
            }
        }
    }




    /**
     *
     * @param string $platform 平台名称
     * @param string $className 类名
     * @param array $params 构造函数参数
     * @return object 类的对象
     * @throws CommonException 如果类不存在或无法实例化
     */
    public static function loadModel(string $platform, string $modelName, array $params = []): object
    {
        $classPath = 'app\\platform\\' . $platform . '\\app\\common\\model\\' . $modelName . '\\' . ucfirst($platform) . ucfirst($modelName) . 'Model';

        if (!class_exists($classPath)) {
            throw new CommonException("Class {$classPath} not found");
        }
        return App::make($classPath, $params);
    }


    /**
     * 加载公共服务
     *
     * @param string $platform 平台名称
     * @param string $serviceName 服务名称
     * @param array $params 构造函数参数
     * @return object 类的对象
     * @throws CommonException 如果类不存在或无法实例化
     */
    public static function loadCommonService(string $platform, string $serviceName, array $params = []): object
    {
        $classPath = 'app\\platform\\' . $platform . '\\app\\common\\service\\' . $serviceName;

        if (!class_exists($classPath)) {
            throw new CommonException("Class {$classPath} not found");
        }

        return App::make($classPath, $params);
    }



    /**
     * 通过提交的参数，自动返回一个类的对象
     *
     * @param string $platform 平台名称
     * @param string $className 类名
     * @param array $params 构造函数参数
     * @return object 类的对象
     * @throws CommonException 如果类不存在或无法实例化
     */
    public static function loadClass(string $platform, string $className, array $params = []): object
    {
        $classPath = 'app\\platform\\' . $platform . '\\app\\adminapi\\controller\\' . $className;

        if (!class_exists($classPath)) {
            throw new CommonException("Class {$classPath} not found");
        }

        return App::make($classPath, $params);
    }


    public static function loadConfigClass(string $platform, string $className, array $params = []): object {
        $classPath = 'app\\platform\\' . $platform . '\\config\\' . $className;

        if (!class_exists($classPath)) {
            throw new CommonException("Class {$classPath} not found");
        }

        return App::make($classPath, $params);
    }











}
