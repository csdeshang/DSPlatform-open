<?php

namespace app\adminapi\controller\trade;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\trade\TradeRefundLogService;

/**
 * @OA\Tag(name="admin-api/trade/TradeRefundLog", description="交易退款日志管理接口")
 */
class TradeRefundLog extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/trade/refund-logs/pages",
     *     summary="获取交易退款日志分页列表",
     *     tags={"admin-api/trade/TradeRefundLog"},
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
     *         name="out_trade_no",
     *         in="query",
     *         required=false,
     *         description="商户订单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="trade_no",
     *         in="query",
     *         required=false,
     *         description="支付订单号",
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
     *         name="refund_status",
     *         in="query",
     *         required=false,
     *         description="退款状态",
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
    public function getTradeRefundLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'out_trade_no' => input('param.out_trade_no'),
            'trade_no' => input('param.trade_no'),
            'out_refund_no' => input('param.out_refund_no'),
            'refund_status' => input('param.refund_status'),
            'pay_amount_min' => input('param.pay_amount_min'),
            'pay_amount_max' => input('param.pay_amount_max'),
        );

        
        $list = (new TradeRefundLogService())->getTradeRefundLogPages($data);
        return ds_json_success('操作成功',$list);

    }

}