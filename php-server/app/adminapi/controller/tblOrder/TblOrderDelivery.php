<?php

namespace app\adminapi\controller\tblOrder;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\order\TblOrderDeliveryService;

/**
 * @OA\Tag(name="admin-api/tblOrder/TblOrderDelivery", description="订单配送管理接口")
 */
class TblOrderDelivery extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/deliveries/pages",
     *     summary="获取订单配送分页列表",
     *     tags={"admin-api/tblOrder/TblOrderDelivery"},
     *     @OA\Parameter(
     *         name="rider_id",
     *         in="query",
     *         required=false,
     *         description="配送员ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="delivery_method",
     *         in="query",
     *         required=false,
     *         description="配送方式",
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
    public function getTblOrderDeliveryPages()
    {
        $data = array(
            'rider_id' => input('param.rider_id'),
            'delivery_method' => input('param.delivery_method'),
        );


        $result = (new TblOrderDeliveryService())->getTblOrderDeliveryPages($data);
        return ds_json_success('操作成功', $result);
    }

}