<?php

namespace app\adminapi\controller\trade;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\trade\TradeTransferLogService;

/**
 * @OA\Tag(name="admin-api/trade/TradeTransferLog", description="交易转账日志管理接口")
 */
class TradeTransferLog extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/trade/transfer-logs/pages",
     *     summary="获取交易转账日志分页列表",
     *     tags={"admin-api/trade/TradeTransferLog"},
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
     *         name="out_transfer_no",
     *         in="query",
     *         required=false,
     *         description="商户转账单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="transfer_no",
     *         in="query",
     *         required=false,
     *         description="转账单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="transfer_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小转账金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="transfer_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大转账金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="transfer_status",
     *         in="query",
     *         required=false,
     *         description="转账状态",
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
    public function getTradeTransferLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'out_transfer_no' => input('param.out_transfer_no'),
            'transfer_no' => input('param.transfer_no'),
            'transfer_amount_min' => input('param.transfer_amount_min'),
            'transfer_amount_max' => input('param.transfer_amount_max'),
            'transfer_status' => input('param.transfer_status'),
        );

        
        $list = (new TradeTransferLogService())->getTradeTransferLogPages($data);
        return ds_json_success('操作成功',$list);

    }

}