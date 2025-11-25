<?php
namespace app\adminapi\controller\distributor;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\distributor\DistributorOrderService;

/**
 * @OA\Tag(
 *     name="admin-api/distributor/DistributorOrder",
 *     description="分销商订单管理接口"
 * )
 */
class DistributorOrder extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/distributor/orders/pages",
     *     summary="获取分销商订单分页列表",
     *     tags={"admin-api/distributor/DistributorOrder"},
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
     *         name="commission_status",
     *         in="query",
     *         required=false,
     *         description="佣金状态",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_user_id",
     *         in="query",
     *         required=false,
     *         description="分销商用户ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string", example="张三")
     *     ),
     *     @OA\Parameter(
     *         name="goods_name",
     *         in="query",
     *         required=false,
     *         description="商品名称",
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
     *         name="commission_type",
     *         in="query",
     *         required=false,
     *         description="佣金类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="commission_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小佣金金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="commission_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大佣金金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pay_price_min",
     *         in="query",
     *         required=false,
     *         description="最小支付金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pay_price_max",
     *         in="query",
     *         required=false,
     *         description="最大支付金额",
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
    public function getDistributorOrderPages()
    {
        $data = array(
            'order_id' => input('param.order_id', 0, 'int'),
            'goods_id' => input('param.goods_id', 0, 'int'),
            'commission_status' => input('param.commission_status'),
            'distributor_user_id' => input('param.distributor_user_id', 0, 'int'),
            'username' => input('param.username'),
            'goods_name' => input('param.goods_name'),
            'store_name' => input('param.store_name'),
            'commission_type' => input('param.commission_type'),
            'commission_amount_min' => input('param.commission_amount_min'),
            'commission_amount_max' => input('param.commission_amount_max'),
            'pay_price_min' => input('param.pay_price_min'),
            'pay_price_max' => input('param.pay_price_max'),
        );

        $result = (new DistributorOrderService())->getDistributorOrderPages($data);
        return ds_json_success('操作成功', $result);
    }


    /**
     * @OA\Get(
     *     path="/adminapi/distributor/orders/list",
     *     summary="获取分销商订单列表",
     *     tags={"admin-api/distributor/DistributorOrder"},
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="string")
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
    public function getDistributorOrderList()
    {
        $data = array(
            'order_id' => input('param.order_id'),
        );

        $result = (new DistributorOrderService())->getDistributorOrderList($data);
        return ds_json_success('操作成功', $result);
    }

}


