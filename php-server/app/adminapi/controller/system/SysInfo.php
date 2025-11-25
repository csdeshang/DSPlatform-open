<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use think\facade\Db;


/**
 * 系统信息控制器
 * 专门处理系统基本信息、版本信息、环境信息等
 * @OA\Tag(name="admin-api/system/SysInfo", description="系统信息管理接口")
 */
class SysInfo extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/system/system-info",
     *     summary="获取系统信息",
     *     tags={"admin-api/system/SysInfo"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getSysInfo()
    {

        $data = [
            'environment' => $this->getEnvironmentInfo(),
            'version' => $this->getVersionInfo(),
        ];
        return ds_json_success('操作成功', $data);
    }

    // 获取环境信息
    private function getEnvironmentInfo(): array
    {
        return [
            // php 版本
            'php_version' => PHP_VERSION,
            // 服务器软件
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            // 服务器操作系统
            'server_os' => PHP_OS,
            // tp 版本
            'framework_version' => 'ThinkPHP ' . \think\App::VERSION,
            // 数据库版本
            'database_version' => $this->getDatabaseVersion(),
            // 域名
            'domain' => $_SERVER['HTTP_HOST'],
            // 安装时间
            'install_time' => env('INSTALL_TIME'),
            // 服务器时间
            'server_time' => date('Y-m-d H:i:s'),
            // 时区
            'timezone' => date_default_timezone_get(),
            // zlib
            'zlib' => function_exists('gzclose') ? 'YES' : 'NO',
            // gd
            'gd' => function_exists('gd_info') ? 'YES' : 'NO',
            // curl
            'curl' => function_exists('curl_version') ? 'YES' : 'NO',
            // 文件上传
            'file_uploads' => ini_get('file_uploads') ? 'YES' : 'NO',
            // 上传文件最大限制
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            // 最大执行时间
            'max_execution_time' => ini_get('max_execution_time'),
            // 最大内存限制
            'memory_limit' => ini_get('memory_limit'),
        ];
    }

    // 获取版本信息
    private function getVersionInfo(): array
    {

        // 获取系统最新版本
        $curent_version = config('version.version');
        //获取最新版本信息
        $vaules = array(
            'domain'=>$_SERVER['HTTP_HOST'], 
            'version'=>$curent_version, 
        );
        $service_url = "http://service.csdeshang.com/index.php/home/Version/dsplatform.html?".http_build_query($vaules);
        //设置超时时间
        $opts = array(
            'http' =>
            array(
                'timeout' => 3
            )
        );
        $context = stream_context_create($opts);
        $service_info = @file_get_contents($service_url,FALSE,$context);
        
        // 获取最新版本信息
        $update_info = json_decode($service_info);

        return [
            // 系统版本
            'current_version' => $curent_version,
            'update_info' => $update_info,
        ];
    }


    /**
     * 获取数据库版本
     */
    private function getDatabaseVersion(): string
    {

        $version = Db::query('SELECT VERSION() as version')[0]['version'] ?? 'Unknown';
        return $version;
    }
}
