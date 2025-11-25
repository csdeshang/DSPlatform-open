<?php

namespace app\adminapi\controller\merchant;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\merchant\MerchantBalanceService;

/**
 * @OA\Tag(name="admin-api/merchant/MerchantBalance", description="商户余额管理接口")
 */
class MerchantBalance extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/merchant/balance-logs/pages",
     *     summary="获取商户余额变动日志分页列表",
     *     tags={"admin-api/merchant/MerchantBalance"},
     *     @OA\Parameter(
     *         name="merchant_id",
     *         in="query",
     *         required=false,
     *         description="商户ID",
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
    public function getMerchantBalanceLogPages(){

        $data = array(
            'merchant_id' => input('param.merchant_id'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
            'change_amount_min' => input('param.change_amount_min'),
            'change_amount_max' => input('param.change_amount_max'),
        );

        $list = (new MerchantBalanceService())->getMerchantBalanceLogPages($data);
        return ds_json_success('操作成功',$list);

    }

    /**
     * @OA\Put(
     *     path="/adminapi/merchant/merchants/{id}/balance",
     *     summary="修改商户余额",
     *     tags={"admin-api/merchant/MerchantBalance"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="商户ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="修改余额信息",
     *         @OA\JsonContent(
     *             required={"change_mode", "change_amount"},
     *             @OA\Property(property="change_mode", type="integer", example=1, description="变动模式（1:增加 2:减少）"),
     *             @OA\Property(property="change_amount", type="number", format="decimal", example=100.00, description="变动金额")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function modifyMerchantBalance($id){
        $data = array(
            'merchant_id' => $id,
            'change_mode' => input('param.change_mode'),
            'change_amount' => number_format(input('param.change_amount'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\merchant\validate\MerchantBalanceValidate.modify');

        (new MerchantBalanceService())->modifyMerchantBalance($data);

        return ds_json_success('操作成功');
    }

}