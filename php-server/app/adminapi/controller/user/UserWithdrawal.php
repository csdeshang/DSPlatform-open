<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserWithdrawalService;

/**
 * @OA\Tag(name="admin-api/user/UserWithdrawal", description="用户提现管理接口")
 */
class UserWithdrawal extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/withdrawal-logs/pages",
     *     summary="获取用户提现日志分页列表",
     *     tags={"admin-api/user/UserWithdrawal"},
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
     *         description="转账单号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="withdrawal_amount_min",
     *         in="query",
     *         required=false,
     *         description="最小提现金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="withdrawal_amount_max",
     *         in="query",
     *         required=false,
     *         description="最大提现金额",
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
    public function getUserWithdrawalLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'out_transfer_no' => input('param.out_transfer_no'),
            'withdrawal_amount_min' => input('param.withdrawal_amount_min'),
            'withdrawal_amount_max' => input('param.withdrawal_amount_max'),
        );
        $list = (new UserWithdrawalService())->getUserWithdrawalLogPages($data);
        return ds_json_success('操作成功',$list);

    }

    /**
     * @OA\Get(
     *     path="/adminapi/user/withdrawal-logs/{id}",
     *     summary="获取用户提现日志详情",
     *     tags={"admin-api/user/UserWithdrawal"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="提现日志ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="提现日志未找到"
     *     )
     * )
     */
    public function getUserWithdrawalLogInfo($id){

        $info = (new UserWithdrawalService())->getUserWithdrawalLogInfo($id);
        return ds_json_success('操作成功',$info);
    }


    /**
     * @OA\Post(
     *     path="/adminapi/user/withdrawal-logs/{id}/operation",
     *     summary="管理员操作提现",
     *     tags={"admin-api/user/UserWithdrawal"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="提现记录ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="提现操作信息",
     *         @OA\JsonContent(
     *             required={"transfer_type", "status"},
     *             @OA\Property(property="transfer_type", type="string", example="bank", description="转账类型"),
     *             @OA\Property(property="transfer_remark", type="string", example="银行转账", description="转账备注"),
     *             @OA\Property(property="status", type="string", example="success", description="操作状态"),
     *             @OA\Property(property="operation_remark", type="string", example="操作成功", description="操作备注")
     *         )
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
    public function operationUserWithdrawal($id){
        $data = array(
            'transfer_type' => input('param.transfer_type'),
            'transfer_remark' => input('param.transfer_remark'),    
            'status' => input('param.status'),
            'operation_remark' => input('param.operation_remark'),
        );

        // 验证器
        $this->validate(array_merge($data, ['id' => $id]), 'app\adminapi\controller\user\validate\UserWithdrawalValidate.operation');

        $result = (new UserWithdrawalService())->operationUserWithdrawal($id,$data);
        return ds_json_success('操作成功',$result);
    }
    



}