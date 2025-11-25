<?php

namespace app\adminapi\controller\tblOrder;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\order\TblOrderService;

/**
 * @OA\Tag(name="admin-api/tblOrder/TblOrder", description="订单管理接口")
 */
class TblOrder extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/orders/pages",
     *     summary="获取订单分页列表",
     *     tags={"admin-api/tblOrder/TblOrder"},
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
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         required=false,
     *         description="店铺ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="order_sn",
     *         in="query",
     *         required=false,
     *         description="订单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *     @OA\Parameter(
     *         name="out_trade_no",
     *         in="query",
     *         required=false,
     *         description="支付单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="trade_no",
     *         in="query",
     *         required=false,
     *         description="交易号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="delivery_method",
     *         in="query",
     *         required=false,
     *         description="交付方式",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_status",
     *         in="query",
     *         required=false,
     *         description="订单状态",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_evaluate",
     *         in="query",
     *         required=false,
     *         description="是否评价（0:未评价 1:已评价）",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="refund_status",
     *         in="query",
     *         required=false,
     *         description="退款状态",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_refunding",
     *         in="query",
     *         required=false,
     *         description="是否退款中（0:否 1:是）",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pay_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小支付金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pay_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大支付金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="merchant_name",
     *         in="query",
     *         required=false,
     *         description="商户名称",
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
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getTblOrderPages()
    {
        $data = array(
            'platform' => input('param.platform'),
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'store_id' => input('param.store_id'),
            'merchant_name' => input('param.merchant_name'),
            'store_name' => input('param.store_name'),
            'goods_name' => input('param.goods_name'),
            'order_sn' => input('param.order_sn'),
            'order_id' => input('param.order_id'),
            'out_trade_no' => input('param.out_trade_no'),
            'trade_no' => input('param.trade_no'),
            'delivery_method' => input('param.delivery_method'),
            'order_status' => input('param.order_status'),
            'is_evaluate' => input('param.is_evaluate'),
            'refund_status' => input('param.refund_status'),
            'is_refunding' => input('param.is_refunding'),
            'pay_amount_min' => input('param.pay_amount_min'),
            'pay_amount_max' => input('param.pay_amount_max'),
        );


        $result = (new TblOrderService())->getTblOrderPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/orders/{id}",
     *     summary="获取订单详情",
     *     tags={"admin-api/tblOrder/TblOrder"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="订单ID",
     *         @OA\Schema(type="integer")
     *     ),
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
    public function getTblOrderInfo($id)
    {
        $result = (new TblOrderService())->getTblOrderInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/goods/list",
     *     summary="获取订单商品列表",
     *     tags={"admin-api/tblOrder/TblOrder"},
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="goods_id",
     *         in="query",
     *         required=false,
     *         description="商品ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblOrderGoodsList()
    {
        $data = array(
            'order_id' => input('param.order_id', 0, 'int'),
            'goods_id' => input('param.goods_id', 0, 'int'),
        );

        $result = (new TblOrderService())->getTblOrderGoodsList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/goods/pages",
     *     summary="获取订单商品分页列表",
     *     tags={"admin-api/tblOrder/TblOrder"},
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="goods_id",
     *         in="query",
     *         required=false,
     *         description="商品ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string")
     *     ),
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
    public function getTblOrderGoodsPages()
    {
        $data = array(
            'order_id' => input('param.order_id', 0, 'int'),
            'goods_id' => input('param.goods_id', 0, 'int'),
            'username' => input('param.username'),
        );

        $result = (new TblOrderService())->getTblOrderGoodsPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/order-logs",
     *     summary="获取订单日志列表",
     *     tags={"admin-api/tblOrder/TblOrder"},
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=true,
     *         description="订单ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblOrderLogList()
    {
        $data = array(
            'order_id' => (int) input('param.order_id'),
        );
        $result = (new TblOrderService())->getTblOrderLogList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/order-pay-logs",
     *     summary="获取订单支付日志列表",
     *     tags={"admin-api/tblOrder/TblOrder"},
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="order_merge_id",
     *         in="query",
     *         required=false,
     *         description="合并支付订单ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblOrderPayLogList()
    {
        $data = array(
            // 订单id
            'order_id' => (int) input('param.order_id'),
            // 合并支付订单id
            'order_merge_id' => (int) input('param.order_merge_id'),

        );
        $result = (new TblOrderService())->getTblOrderPayLogList($data);
        return ds_json_success('操作成功', $result);
    }

}