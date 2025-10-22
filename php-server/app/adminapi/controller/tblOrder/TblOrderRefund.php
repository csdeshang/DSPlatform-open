<?php

namespace app\adminapi\controller\tblOrder;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\order\TblOrderRefundService;

/**
 * @OA\Tag(name="admin-api/tblOrder/TblOrderRefund", description="订单退款管理接口")
 */
class TblOrderRefund extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/refund/pages",
     *     summary="获取订单退款分页列表",
     *     tags={"admin-api/tblOrder/TblOrderRefund"},
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
     *     @OA\Parameter(
     *         name="out_refund_no",
     *         in="query",
     *         required=false,
     *         description="退款单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="refund_type",
     *         in="query",
     *         required=false,
     *         description="退款类型",
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
     *         name="refund_method",
     *         in="query",
     *         required=false,
     *         description="退款方式",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="refund_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小退款金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="refund_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大退款金额",
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
    public function getTblOrderRefundPages()
    {
        $data = array(
            'platform' => input('param.platform'),
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'store_id' => input('param.store_id'),
            'store_name' => input('param.store_name'),
            'goods_name' => input('param.goods_name'),
            'out_refund_no' => input('param.out_refund_no'),
            'refund_type' => input('param.refund_type'),
            'refund_status' => input('param.refund_status'),
            'refund_method' => input('param.refund_method'),
            'refund_amount_min' => input('param.refund_amount_min'),
            'refund_amount_max' => input('param.refund_amount_max'),
        );


        $result = (new TblOrderRefundService())->getTblOrderRefundPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/refund/list",
     *     summary="获取订单退款列表",
     *     tags={"admin-api/tblOrder/TblOrderRefund"},
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
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblOrderRefundList()
    {
        $data = array(
            'order_id' => (int) input('param.order_id'),
        );
        $result = (new TblOrderRefundService())->getTblOrderRefundList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/refund/info/{id}",
     *     summary="获取订单退款详情",
     *     tags={"admin-api/tblOrder/TblOrderRefund"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="退款ID",
     *         @OA\Schema(type="integer")
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
    public function getTblOrderRefundInfo($id)
    {
        $result = (new TblOrderRefundService())->getTblOrderRefundInfo((int)$id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-order/refund/retry/{id}",
     *     summary="重新发起退款",
     *     tags={"admin-api/tblOrder/TblOrderRefund"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="退款ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="退款ID不能为0",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="退款ID不能为0")
     *         )
     *     )
     * )
     */
    public function retryTblOrderRefund($id)
    {
        $refund_id = (int) $id;
        if ($refund_id <= 0) {
            return ds_json_error('退款ID不能为0');
        }
        $result = (new TblOrderRefundService())->retryTblOrderRefund($refund_id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/refund/log/list/{id}",
     *     summary="获取订单退款日志列表",
     *     tags={"admin-api/tblOrder/TblOrderRefund"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="退款ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblOrderRefundLogList($id)
    {
        $result = (new TblOrderRefundService())->getTblOrderRefundLogList((int)$id);
        return ds_json_success('操作成功', $result);
    }

}