<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\system\SysClearService;
use app\deshang\utils\CacheUtil;


/**
 * @OA\Tag(name="admin-api/system/SysClear", description="系统清理管理接口")
 */
// 系统清理
class SysClear extends BaseAdminController
{

    /**
     * @OA\Post(
     *     path="/adminapi/system/clear/cache",
     *     summary="清理系统缓存",
     *     tags={"admin-api/system/SysClear"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    // 清理缓存
    public function clearCache()
    {
        CacheUtil::clear(); 
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/clear/logs",
     *     summary="清理系统日志",
     *     tags={"admin-api/system/SysClear"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function clearLogs()
    {
        $root = app()->getRootPath() . 'runtime' . DIRECTORY_SEPARATOR;

        // 删除的目录
        $deletePaths = [
            $root . 'log',
            $root . 'adminapi',
            $root . 'api',
            $root . 'bloggerapi',
            $root . 'merchantapi',
            $root . 'riderapi',
            $root . 'storeapi',
            $root . 'techapi'
        ];

        foreach ($deletePaths as $path) {
            deleteFileRecursive($path);
        }
        return ds_json_success('操作成功');
    }



    /**
     * @OA\Post(
     *     path="/adminapi/system/clear/access-logs",
     *     summary="清理系统访问日志[数据库]",
     *     tags={"admin-api/system/SysClear"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function clearSysAccessLogs()
    {
        $result = (new SysClearService())->clearTableData('sys_access_logs');
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/clear/error-logs",
     *     summary="清理系统错误日志[数据库]",
     *     tags={"admin-api/system/SysClear"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function clearSysErrorLog()
    {
        $result = (new SysClearService())->clearTableData('sys_error_logs');
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/clear/admin-logs",
     *     summary="清理管理员日志[数据库]",
     *     tags={"admin-api/system/SysClear"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function clearAdminLogs()
    {
        $result = (new SysClearService())->clearTableData('admin_logs');
        return ds_json_success('操作成功', $result);
    }



}
