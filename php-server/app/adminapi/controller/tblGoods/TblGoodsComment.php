<?php

namespace app\adminapi\controller\tblGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\goods\TblGoodsCommentService;

/**
 * @OA\Tag(name="admin-api/tblGoods/TblGoodsComment", description="商品评论管理接口")
 */
class TblGoodsComment extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/comment/pages",
     *     summary="获取商品评论分页列表",
     *     tags={"admin-api/tblGoods/TblGoodsComment"},
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=false,
     *         description="平台类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="goods_id",
     *         in="query",
     *         required=false,
     *         description="商品ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         required=false,
     *         description="店铺ID",
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
     *     @OA\Parameter(
     *         name="is_anonymous",
     *         in="query",
     *         required=false,
     *         description="是否匿名 0实名 1匿名",
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
     *         name="store_name",
     *         in="query",
     *         required=false,
     *         description="店铺名称",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="goods_name",
     *         in="query",
     *         required=false,
     *         description="商品名称",
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
    public function getTblGoodsCommentPages()
    {
        $data = [
            'platform' => input('param.platform', ''),
            'user_id' => input('param.user_id', ''),
            'goods_id' => input('param.goods_id', ''),
            'store_id' => input('param.store_id', ''),
            'order_id' => input('param.order_id', ''),
            'is_show' => input('param.is_show', ''),
            'is_reply' => input('param.is_reply', ''),
            'is_anonymous' => input('param.is_anonymous', ''),
            'username' => input('param.username', ''),
            'store_name' => input('param.store_name', ''),
            'goods_name' => input('param.goods_name', ''),
        ];

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsCommentValidate.pages');

        $result = (new TblGoodsCommentService())->getTblGoodsCommentPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-goods/comment/toggle-field",
     *     summary="切换商品评论字段状态",
     *     tags={"admin-api/tblGoods/TblGoodsComment"},
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
    public function toggleTblGoodsCommentField()
    {
        $data = [
            'id' => input('param.id'),
            'field' => input('param.field')
        ];

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsCommentValidate.toggle');

        $result = (new TblGoodsCommentService())->toggleTblGoodsCommentField($data);
        return ds_json_success('切换成功', $result);
    }


}