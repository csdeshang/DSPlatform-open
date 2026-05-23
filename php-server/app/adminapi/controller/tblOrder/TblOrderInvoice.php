<?php

namespace app\adminapi\controller\tblOrder;

use app\adminapi\service\order\TblOrderInvoiceService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/tblOrder/TblOrderInvoice", description="订单开票申请查询（只读）")
 */
class TblOrderInvoice extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/invoices/pages",
     *     summary="开票申请分页列表",
     *     tags={"admin-api/tblOrder/TblOrderInvoice"},
     *     description="跨店铺查询，仅查看",
     *     @OA\Parameter(name="platform", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="invoice_status", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="order_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="order_sn", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="username", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="user_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="store_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="store_name", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="merchant_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="merchant_name", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="goods_name", in="query", required=false, @OA\Schema(type="string")),
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
    public function getTblOrderInvoicePages()
    {
        $data = [
            'platform' => input('param.platform', ''),
            'invoice_status' => input('param.invoice_status', ''),
            'order_id' => input('param.order_id', ''),
            'order_sn' => input('param.order_sn', ''),
            'username' => input('param.username', ''),
            'user_id' => input('param.user_id', ''),
            'store_id' => input('param.store_id', ''),
            'store_name' => input('param.store_name', ''),
            'merchant_id' => input('param.merchant_id', ''),
            'merchant_name' => input('param.merchant_name', ''),
            'goods_name' => input('param.goods_name', ''),
        ];
        $this->validate($data, 'app\adminapi\controller\tblOrder\validate\TblOrderInvoiceValidate.pages');
        $result = (new TblOrderInvoiceService())->getTblOrderInvoicePages($data);

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-order/invoices/{id}",
     *     summary="开票申请详情",
     *     tags={"admin-api/tblOrder/TblOrderInvoice"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
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
    public function getTblOrderInvoiceInfo($id)
    {
        $this->validate(['invoice_id' => $id], 'app\adminapi\controller\tblOrder\validate\TblOrderInvoiceValidate.info');
        $result = (new TblOrderInvoiceService())->getTblOrderInvoiceInfo((int) $id);

        return ds_json_success('操作成功', $result);
    }
}
