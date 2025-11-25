<?php

namespace app\adminapi\controller\distributor;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\distributor\DistributorBalanceService;

/**
 * @OA\Tag(
 *     name="admin-api/distributor/DistributorBalance",
 *     description="分销商余额管理接口"
 * )
 */
class DistributorBalance extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/distributor/balance-logs/pages",
     *     summary="获取分销商余额变动日志分页列表",
     *     tags={"admin-api/distributor/DistributorBalance"},
     *     @OA\Parameter(
     *         name="distributor_user_id",
     *         in="query",
     *         required=false,
     *         description="分销商用户ID",
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
     *         @OA\Schema(type="string", example="张三")
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
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getDistributorBalanceLogPages(){

        $data = array(
            'distributor_user_id' => input('param.distributor_user_id'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
            'username' => input('param.username'),
            'change_amount_min' => input('param.change_amount_min'),
            'change_amount_max' => input('param.change_amount_max'),
        );


        
        $list = (new DistributorBalanceService())->getDistributorBalanceLogPages($data);
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Put(
     *     path="/adminapi/distributor/distributors/{id}/balance",
     *     summary="修改分销商余额",
     *     tags={"admin-api/distributor/DistributorBalance"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="分销商用户ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="修改余额信息",
     *         @OA\JsonContent(
     *              required={"change_mode", "change_amount"},
     *              @OA\Property(property="change_mode", type="integer", example=1, description="变动模式（1:增加 2:减少）"),
     *              @OA\Property(property="change_amount", type="number", format="decimal", example=100.00, description="变动金额")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *              @OA\Property(property="code", type="integer", example=10000),
     *              @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function modifyDistributorBalance($id){
        $data = array(
            'distributor_user_id' => $id,
            'change_mode' => input('param.change_mode'),
            'change_amount' => number_format(input('param.change_amount'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\distributor\validate\DistributorBalanceValidate.modify');

        (new DistributorBalanceService())->modifyDistributorBalance($data);

        return ds_json_success('操作成功');
    }

}