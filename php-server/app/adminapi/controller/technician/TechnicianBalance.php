<?php

namespace app\adminapi\controller\technician;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\technician\TechnicianBalanceService;

/**
 * @OA\Tag(name="admin-api/technician/TechnicianBalance", description="管理端师傅余额管理接口")
 */
class TechnicianBalance extends BaseAdminController
{
    /**
     * 获取师傅余额日志分页列表
     * @OA\Get(
     *     path="/adminapi/technician/balance-log/pages",
     *     tags={"admin-api/technician/TechnicianBalance"},
     *     summary="获取师傅余额日志分页列表",
     *     description="获取师傅余额变动记录的分页列表",
     *     @OA\Parameter(
     *         name="technician_id",
     *         in="query",
     *         required=false,
     *         description="师傅ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="change_type",
     *         in="query",
     *         required=false,
     *         description="变动类型",
     *         @OA\Schema(type="string", example="system")
     *     ),
     *     @OA\Parameter(
     *         name="change_mode",
     *         in="query",
     *         required=false,
     *         description="变动方式",
     *         @OA\Schema(type="integer", example=1)
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
    public function getTechnicianBalanceLogPages()
    {
        $data = array(
            'technician_id' => input('param.technician_id'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
        );
        $list = (new TechnicianBalanceService())->getTechnicianBalanceLogPages($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * 修改师傅余额
     * @OA\Post(
     *     path="/adminapi/technician/balance/modifyTechnicianBalance",
     *     tags={"admin-api/technician/TechnicianBalance"},
     *     summary="修改师傅余额",
     *     description="管理员调整师傅余额",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"technician_id", "change_mode", "change_amount"},
     *             @OA\Property(property="technician_id", type="integer", description="师傅ID", example=1),
     *             @OA\Property(property="change_mode", type="integer", description="变动方式 1增加 2减少", example=1),
     *             @OA\Property(property="change_amount", type="number", description="变动金额", example=100.00),
     *             @OA\Property(property="change_desc", type="string", description="变动说明", example="管理员调整")
     *         )
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
    public function modifyTechnicianBalance()
    {
        $data = array(
            'technician_id' => input('param.technician_id'),
            'change_mode' => input('param.change_mode'),
            'change_amount' => number_format(input('param.change_amount'), 2, '.', ''),
            'change_desc' => input('param.change_desc', '管理员操作'),
        );

        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianBalance.modify');

        (new TechnicianBalanceService())->modifyTechnicianBalance($data);

        return ds_json_success('操作成功');
    }
} 