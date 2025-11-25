<?php

namespace app\adminapi\controller\system;

use app\adminapi\service\system\SysAccessLogsService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/system/SysAccessLogs", description="系统访问日志相关接口")
 */
class SysAccessLogs extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/access-logs/pages",
     *     tags={"admin-api/system/SysAccessLogs"},
     *     summary="获取系统访问日志分页列表",
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
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="管理员账号",
     *         @OA\Schema(type="string", example="admin")
     *     ),
     *     @OA\Parameter(
     *         name="ip",
     *         in="query",
     *         required=false,
     *         description="IP地址",
     *         @OA\Schema(type="string", example="192.168.1.1")
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
     *         description="成功获取系统访问日志分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysAccesslogsPages()
    {
        $data = array(
            'username' => input('param.username'),
            'ip' => input('param.ip'),
            'duration_min' => input('param.duration_min'),
            'duration_max' => input('param.duration_max'),
        );
        $result = (new SysAccessLogsService())->getSysAccesslogsPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/access-logs/{id}",
     *     tags={"admin-api/system/SysAccessLogs"},
     *     summary="获取系统访问日志详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取系统访问日志详情",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
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
    public function getSysAccesslogsInfo($id)
    {
        $result = (new SysAccessLogsService())->getSysAccesslogsInfo($id);
        return ds_json_success('操作成功', $result);
    }
}