<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\rider\RiderBalanceService;

/**
 * @OA\Tag(
 *     name="admin-api/rider/RiderBalance",
 *     description="骑手余额管理接口"
 * )
 */
class RiderBalance extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/rider/balance-logs/pages",
     *     summary="获取骑手余额变动记录分页列表",
     *     tags={"admin-api/rider/RiderBalance"},
     *     @OA\Parameter(
     *         name="rider_id",
     *         in="query",
     *         description="骑手ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="change_type",
     *         in="query",
     *         description="变动类型",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="change_mode",
     *         in="query",
     *         description="变动方式",
     *         required=false,
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
    public function getRiderBalanceLogPages(){

        $data = array(
            'rider_id' => input('param.rider_id'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
        );
        $list = (new RiderBalanceService())->getRiderBalanceLogPages($data);
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Put(
     *     path="/adminapi/rider/riders/{id}/balance",
     *     summary="修改骑手余额",
     *     tags={"admin-api/rider/RiderBalance"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="骑手ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="骑手余额修改所需信息",
     *         @OA\JsonContent(
     *             required={"change_mode", "change_amount"},
     *             @OA\Property(property="change_mode", type="string", example="admin_adjust", description="变动模式"),
     *             @OA\Property(property="change_amount", type="number", format="decimal", example=100.00, description="变动金额")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function modifyRiderBalance($id){
        $data = array(
            'rider_id' => $id,
            'change_mode' => input('param.change_mode'),
            'change_amount' => number_format(input('param.change_amount'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderBalance.modify');

        (new RiderBalanceService())->modifyRiderBalance($data);

        return ds_json_success('操作成功');
    }

}