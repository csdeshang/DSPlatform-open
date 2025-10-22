<?php

namespace app\adminapi\controller\system;

use app\adminapi\service\system\SysErrorLogsService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/system/SysErrorLogs", description="系统错误日志相关接口")
 */
class SysErrorLogs extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/error-logs/pages",
     *     tags={"admin-api/system/SysErrorLogs"},
     *     summary="获取系统错误日志分页列表",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="controller",
     *         in="query",
     *         required=false,
     *         description="控制器名称",
     *         @OA\Schema(type="string", example="Admin")
     *     ),
     *     @OA\Parameter(
     *         name="root",
     *         in="query",
     *         required=false,
     *         description="根目录",
     *         @OA\Schema(type="string", example="/var/www/html")
     *     ),
     *     @OA\Parameter(
     *         name="ip",
     *         in="query",
     *         required=false,
     *         description="IP地址",
     *         @OA\Schema(type="string", example="192.168.1.1")
     *     ),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=false,
     *         description="错误代码",
     *         @OA\Schema(type="string", example="500")
     *     ),
     *     @OA\Parameter(
     *         name="duration_min",
     *         in="query",
     *         required=false,
     *         description="最小请求耗时(毫秒)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="duration_max",
     *         in="query",
     *         required=false,
     *         description="最大请求耗时(毫秒)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取系统错误日志分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysErrorLogsPages()
    {
        $data = array(
            'controller' => input('param.controller'),
            'root' => input('param.root'),
            'ip' => input('param.ip'),
            'code' => input('param.code'),
            'duration_min' => input('param.duration_min'),
            'duration_max' => input('param.duration_max'),
        );
        $result = (new SysErrorLogsService())->getSysErrorLogsPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/error-logs/{id}",
     *     tags={"admin-api/system/SysErrorLogs"},
     *     summary="获取系统错误日志详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取系统错误日志详情",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="日志未找到"
     *     )
     * )
     */
    public function getSysErrorLogsInfo($id)
    {
        $result = (new SysErrorLogsService())->getSysErrorLogsInfo($id);
        return ds_json_success('操作成功', $result);
    }
}