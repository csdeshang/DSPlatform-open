<?php

namespace app\adminapi\controller\pointsGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\pointsGoods\PointsGoodsOrderService;

/**
 * @OA\Tag(name="admin-api/points-goods/PointsGoodsOrder", description="积分兑换订单管理相关接口")
 */
class PointsGoodsOrder extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/orders/pages",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="获取积分兑换订单分页列表",
     *     @OA\Parameter(
     *         name="order_sn",
     *         in="query",
     *         description="订单号",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="用户ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="goods_id",
     *         in="query",
     *         description="商品ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="goods_name",
     *         in="query",
     *         description="商品名称",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_status",
     *         in="query",
     *         description="订单状态 0:已取消 10:待发货 20:已发货 30:已收货 40:已完成",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 10, 20, 30, 40})
     *     ),
     *     @OA\Parameter(
     *         name="receiver_name",
     *         in="query",
     *         description="收货人姓名",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="用户名",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="receiver_mobile",
     *         in="query",
     *         description="收货人手机",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取积分兑换订单分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getPointsGoodsOrderPages()
    {
        $data = array(
            'order_sn' => input('param.order_sn'),
            'user_id' => input('param.user_id'),
            'goods_id' => input('param.goods_id'),
            'goods_name' => input('param.goods_name'),
            'order_status' => input('param.order_status'),
            'receiver_name' => input('param.receiver_name'),
            'username' => input('param.username'),
            'receiver_mobile' => input('param.receiver_mobile'),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.pages');

        $result = (new PointsGoodsOrderService())->getPointsGoodsOrderPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/orders/{id}",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="获取积分兑换订单详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取积分兑换订单详情",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="订单未找到"
     *     )
     * )
     */
    public function getPointsGoodsOrderInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.info');
        
        $result = (new PointsGoodsOrderService())->getPointsGoodsOrderInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/orders/{id}/logs",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="获取积分兑换订单日志",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取积分兑换订单日志",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array")
     *         )
     *     )
     * )
     */
    public function getPointsGoodsOrderLogs($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.info');
        
        $result = (new PointsGoodsOrderService())->getPointsGoodsOrderLogs($id);
        return ds_json_success('操作成功', $result);
    }


    /**
     * @OA\Put(
     *     path="/adminapi/points-goods/orders/{id}",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="更新积分兑换订单",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="delivery_method", type="string", example="express", description="配送方式 express:快递 delivery:自提"),
     *             @OA\Property(property="receiver_name", type="string", example="张三", description="收货人姓名"),
     *             @OA\Property(property="receiver_mobile", type="string", example="13800138000", description="收货人手机"),
     *             @OA\Property(property="receiver_address", type="string", example="科技园南区", description="收货详细地址"),
     *             @OA\Property(property="express_company", type="string", example="顺丰快递", description="快递公司（可选）"),
     *             @OA\Property(property="express_no", type="string", example="SF1234567890", description="快递单号（可选）"),
     *             @OA\Property(property="remark", type="string", example="备注信息", description="备注"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功更新积分兑换订单",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updatePointsGoodsOrder($id)
    {
        $data = array(
            'id' => (int) $id,
            'delivery_method' => input('param.delivery_method'),
            'receiver_name' => input('param.receiver_name'),
            'receiver_mobile' => input('param.receiver_mobile'),
            'receiver_address' => input('param.receiver_address'),
            'express_company' => input('param.express_company', ''),
            'express_no' => input('param.express_no', ''),
            'remark' => input('param.remark', ''),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.update');

        $result = (new PointsGoodsOrderService())->updatePointsGoodsOrder($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/points-goods/orders/{id}/cancel",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="取消积分兑换订单",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功取消积分兑换订单",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function cancelPointsGoodsOrder($id)
    {
        $data = array('id' => (int) $id);
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.cancel');
        
        $result = (new PointsGoodsOrderService())->cancelPointsGoodsOrder((int) $id);
        return ds_json_success('取消订单成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/points-goods/orders/{id}/ship",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="发货积分兑换订单",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="delivery_method", type="string", example="express", description="配送方式 express:快递 delivery:自提"),
     *             @OA\Property(property="express_company", type="string", example="顺丰快递", description="快递公司（可选）"),
     *             @OA\Property(property="express_no", type="string", example="SF1234567890", description="快递单号（可选）"),
     *             @OA\Property(property="remark", type="string", example="发货备注", description="发货备注"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功发货积分兑换订单",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function shipPointsGoodsOrder($id)
    {
        $data = array(
            'id' => (int) $id,
            'delivery_method' => input('param.delivery_method'),
            'express_company' => input('param.express_company'),
            'express_no' => input('param.express_no'),
            'remark' => input('param.remark', ''),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.ship');
        
        $result = (new PointsGoodsOrderService())->shipPointsGoodsOrder((int) $id, $data);
        return ds_json_success('发货成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/points-goods/orders/{id}/confirm",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="确认收货积分兑换订单",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功确认收货积分兑换订单",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function confirmPointsGoodsOrder($id)
    {
        $data = array('id' => (int) $id);
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.confirm');
        
        $result = (new PointsGoodsOrderService())->confirmPointsGoodsOrder((int) $id);
        return ds_json_success('确认收货成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/points-goods/orders/{id}",
     *     tags={"admin-api/points-goods/PointsGoodsOrder"},
     *     summary="删除积分兑换订单",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除积分兑换订单",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="订单未找到"
     *     )
     * )
     */
    public function deletePointsGoodsOrder($id)
    {
        $data = array('id' => (int)$id);
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsOrderValidate.delete');
        
        $result = (new PointsGoodsOrderService())->deletePointsGoodsOrder((int)$id);
        return ds_json_success('操作成功', $result);
    }
}
