<?php

namespace app\adminapi\controller\admin;

use app\adminapi\service\admin\AdminLogsService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/admin/AdminLogs", description="管理员日志相关接口")
 */
class AdminLogs extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/admin/logs/pages",
     *     tags={"admin-api/admin/AdminLogs"},
     *     summary="获取管理员日志分页列表",
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
     *         name="http_code",
     *         in="query",
     *         required=false,
     *         description="HTTP状态码",
     *         @OA\Schema(type="string", example="200")
     *     ),
     *     @OA\Parameter(
     *         name="controller",
     *         in="query",
     *         required=false,
     *         description="控制器名称",
     *         @OA\Schema(type="string", example="Admin")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取管理员日志分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getAdminLogsPages(){
        $data = array(
            'username' => input('param.username'),
            'http_code' => input('param.http_code'),
            'controller' => input('param.controller'),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminLogs.pages');

        $result = (new AdminLogsService())->getAdminLogsPages($data);
        return ds_json_success('操作成功', $result);
    }



    /**
     * @OA\Get(
     *     path="/adminapi/admin/logs/{id}",
     *     tags={"admin-api/admin/AdminLogs"},
     *     summary="获取管理员日志详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取管理员日志详情",
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
    public function getAdminLogsInfo($id){
        $data = array(
            'id' => $id,
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminLogs.info');

        $result = (new AdminLogsService())->getAdminLogsInfo($id);
        return ds_json_success('操作成功', $result);
    }
}