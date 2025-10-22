<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\rider\RiderCommentService;

/**
 * @OA\Tag(name="admin-api/rider/RiderComment", description="骑手评论管理接口")
 */
class RiderComment extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/rider/comment/pages",
     *     summary="获取骑手评论分页列表",
     *     tags={"admin-api/rider/RiderComment"},
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
     *         name="rider_id",
     *         in="query",
     *         required=false,
     *         description="骑手ID",
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
    public function getRiderCommentPages()
    {
        $data = [
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'rider_id' => input('param.rider_id'),
            'order_id' => input('param.order_id'),
            'is_show' => input('param.is_show'),
            'is_reply' => input('param.is_reply'),
        ];

        $result = (new RiderCommentService())->getRiderCommentPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/rider/comment/toggle-field",
     *     summary="切换骑手评论字段状态",
     *     tags={"admin-api/rider/RiderComment"},
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
    public function toggleRiderCommentField()
    {
        $data = [
            'id' => input('param.id'),
            'field' => input('param.field')
        ];

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderCommentValidate.toggle');

        $result = (new RiderCommentService())->toggleRiderCommentField($data);
        return ds_json_success('切换成功', $result);
    }
} 