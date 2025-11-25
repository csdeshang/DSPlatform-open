<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserRechargeService;

/**
 * @OA\Tag(name="admin-api/user/UserRecharge", description="用户充值管理接口")
 */
class UserRecharge extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/recharge-logs/pages",
     *     summary="获取用户充值日志分页列表",
     *     tags={"admin-api/user/UserRecharge"},
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
     *         description="支付平台订单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pay_merchant_id",
     *         in="query",
     *         required=false,
     *         description="支付商户ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="pay_channel",
     *         in="query",
     *         required=false,
     *         description="支付渠道",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pay_scene",
     *         in="query",
     *         required=false,
     *         description="支付场景",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="recharge_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小充值金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="recharge_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大充值金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="recharge_status",
     *         in="query",
     *         required=false,
     *         description="充值状态",
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
    public function getUserRechargeLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'out_trade_no' => input('param.out_trade_no'),
            'trade_no' => input('param.trade_no'),
            'pay_merchant_id' => input('param.pay_merchant_id'),
            'pay_channel' => input('param.pay_channel'),
            'pay_scene' => input('param.pay_scene'),
            'recharge_amount_min' => input('param.recharge_amount_min'),
            'recharge_amount_max' => input('param.recharge_amount_max'),
            'recharge_status' => input('param.recharge_status'),
        );
        $list = (new UserRechargeService())->getUserRechargeLogPages($data);
        return ds_json_success('操作成功',$list);

    }



}