<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserBalanceService;

/**
 * @OA\Tag(name="admin-api/user/UserBalance", description="会员余额管理接口")
 */
class UserBalance extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/balance-log/pages",
     *     summary="获取会员余额变动日志分页列表",
     *     tags={"admin-api/user/UserBalance"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="会员ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="change_type",
     *         in="query",
     *         required=false,
     *         description="变动类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="change_mode",
     *         in="query",
     *         required=false,
     *         description="变动模式（1:增加 2:减少）",
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
     *         name="change_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小变动金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="change_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大变动金额",
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
    public function getUserBalanceLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
            'change_amount_min' => input('param.change_amount_min'),
            'change_amount_max' => input('param.change_amount_max'),
        );

        $list = (new UserBalanceService())->getUserBalanceLogPages($data);
        return ds_json_success('操作成功',$list);

    }

    /**
     * @OA\Post(
     *     path="/adminapi/user/balance/modifyUserBalance",
     *     summary="修改会员余额",
     *     tags={"admin-api/user/UserBalance"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="修改余额信息",
     *         @OA\JsonContent(
     *             required={"user_id", "change_mode", "change_amount"},
     *             @OA\Property(property="user_id", type="integer", example=1, description="会员ID"),
     *             @OA\Property(property="change_mode", type="integer", example=1, description="变动模式（1:增加 2:减少）"),
     *             @OA\Property(property="change_amount", type="number", format="decimal", example=100.00, description="变动金额")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function modifyUserBalance(){
        $data = array(
            'user_id' => input('param.user_id'),
            'change_mode' => input('param.change_mode'),
            'change_amount' => number_format(input('param.change_amount'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\user\validate\UserBalance.modify');

        (new UserBalanceService())->modifyUserBalance($data);

        return ds_json_success('操作成功');
    }

}