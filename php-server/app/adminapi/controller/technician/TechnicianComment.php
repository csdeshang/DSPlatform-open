<?php

namespace app\adminapi\controller\technician;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\technician\TechnicianCommentService;

/**
 * @OA\Tag(name="admin-api/technician/TechnicianComment", description="师傅评论管理接口")
 */
class TechnicianComment extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/technician/comment/pages",
     *     summary="获取师傅评论分页列表",
     *     tags={"admin-api/technician/TechnicianComment"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="technician_id",
     *         in="query",
     *         required=false,
     *         description="师傅ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_show",
     *         in="query",
     *         required=false,
     *         description="是否显示 0隐藏 1显示",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_reply",
     *         in="query",
     *         required=false,
     *         description="是否回复 0未回复 1已回复",
     *         @OA\Schema(type="string")
     *     ),
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
    public function getTechnicianCommentPages()
    {
        $data = [
            'user_id' => input('param.user_id', ''),
            'username' => input('param.username', ''),
            'technician_id' => input('param.technician_id', ''),
            'order_id' => input('param.order_id', ''),
            'is_show' => input('param.is_show', ''),
            'is_reply' => input('param.is_reply', ''),
        ];

        $result = (new TechnicianCommentService())->getTechnicianCommentPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/technician/comment/toggle-field",
     *     summary="切换师傅评论字段状态",
     *     tags={"admin-api/technician/TechnicianComment"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="切换数据",
     *         @OA\JsonContent(
     *             required={"id", "field"},
     *             @OA\Property(property="id", type="integer", example=1, description="评论ID"),
     *             @OA\Property(property="field", type="string", example="is_show", description="字段名")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="切换成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="切换成功")
     *         )
     *     )
     * )
     */
    public function toggleTechnicianCommentField()
    {
        $data = [
            'id' => input('param.id'),
            'field' => input('param.field')
        ];

        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianCommentValidate.toggle');

        $result = (new TechnicianCommentService())->toggleTechnicianCommentField($data);
        return ds_json_success('切换成功', $result);
    }
}
