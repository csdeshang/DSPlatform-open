<?php

namespace app\adminapi\controller\distributor;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\distributor\DistributorApplyService;

/**
 * @OA\Tag(
 *     name="admin-api/distributor/DistributorApply",
 *     description="分销商申请管理接口"
 * )
 */
class DistributorApply extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/distributor/applies/pages",
     *     summary="获取分销商申请分页列表",
     *     tags={"admin-api/distributor/DistributorApply"},
     *     @OA\Parameter(
     *         name="apply_status",
     *         in="query",
     *         required=false,
     *         description="申请状态（0:待审核 1:已通过 2:已拒绝）",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string", example="张三")
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
    public function getDistributorApplyPages(){

        $data = array(
            'apply_status' => input('param.apply_status'),
            'username' => input('param.username'),
        );

        // 验证参数
        $this->validate($data, 'app\adminapi\controller\distributor\validate\DistributorApplyValidate.pages');

        $list = (new DistributorApplyService())->getDistributorApplyPages($data);
        return ds_json_success('操作成功',$list);
    }


    /**
     * @OA\Patch(
     *     path="/adminapi/distributor/applies/{id}/audit",
     *     summary="分销商申请审核",
     *     tags={"admin-api/distributor/DistributorApply"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="申请记录ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="审核信息",
     *         @OA\JsonContent(
     *              required={"apply_status"},
     *              @OA\Property(property="apply_status", type="integer", example=1, description="审核状态（1:通过 2:拒绝）"),
     *              @OA\Property(property="audit_remark", type="string", example="审核通过", description="审核备注（拒绝时必填）")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *              @OA\Property(property="code", type="integer", example=10000),
     *              @OA\Property(property="msg", type="string", example="操作成功"),
     *              @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function auditDistributorApply($id){

        $data = array(
            'id' => $id,
            'apply_status' => input('param.apply_status'),
            'audit_remark' => input('param.audit_remark'),
        );

        // 验证参数
        $this->validate($data, 'app\adminapi\controller\distributor\validate\DistributorApplyValidate.audit');

        $info = (new DistributorApplyService())->auditDistributorApply($data);
        return ds_json_success('操作成功',$info);
    }

}