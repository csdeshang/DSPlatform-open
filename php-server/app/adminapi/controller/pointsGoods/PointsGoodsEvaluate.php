<?php

namespace app\adminapi\controller\pointsGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\pointsGoods\PointsGoodsEvaluateService;

/**
 * @OA\Tag(name="admin-api/points-goods/PointsGoodsEvaluate", description="积分商品评价管理")
 */
class PointsGoodsEvaluate extends BaseAdminController
{
    /**
     * 获取积分商品评价分页列表
     * 
     * @OA\Get(
     *     path="/adminapi/points-goods/evaluate/pages",
     *     tags={"admin-api/points-goods/PointsGoodsEvaluate"},
     *     summary="获取积分商品评价分页列表",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="用户名",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="goods_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="is_show",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="is_anonymous",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="0")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取积分商品评价分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getPointsGoodsEvaluatePages()
    {
        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'goods_id' => input('param.goods_id'),
            'order_id' => input('param.order_id'),
            'is_show' => input('param.is_show'),
            'is_anonymous' => input('param.is_anonymous'),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsEvaluateValidate.pages');

        $result = (new PointsGoodsEvaluateService())->getPointsGoodsEvaluatePages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * 切换积分商品评价字段状态
     * 
     * @OA\Put(
     *     path="/adminapi/points-goods/evaluate/toggle",
     *     tags={"admin-api/points-goods/PointsGoodsEvaluate"},
     *     summary="切换积分商品评价字段状态",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="评价ID"),
     *             @OA\Property(property="field", type="string", example="is_show", description="字段名 is_show:显示状态 is_anonymous:匿名状态"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功切换积分商品评价字段状态",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function togglePointsGoodsEvaluateField()
    {
        $data = array(
            'id' => input('param.id'),
            'field' => input('param.field'),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsEvaluateValidate.toggle');

        $result = (new PointsGoodsEvaluateService())->togglePointsGoodsEvaluateField($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * 商家回复积分商品评价
     * 
     * @OA\Put(
     *     path="/adminapi/points-goods/evaluate/reply",
     *     tags={"admin-api/points-goods/PointsGoodsEvaluate"},
     *     summary="商家回复积分商品评价",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="评价ID"),
     *             @OA\Property(property="reply_content", type="string", example="感谢您的评价", description="回复内容"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功回复积分商品评价",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function replyPointsGoodsEvaluate()
    {
        $data = array(
            'id' => input('param.id'),
            'reply_content' => input('param.reply_content'),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsEvaluateValidate.reply');

        $result = (new PointsGoodsEvaluateService())->replyPointsGoodsEvaluate($data);
        return ds_json_success('操作成功', $result);
    }
}