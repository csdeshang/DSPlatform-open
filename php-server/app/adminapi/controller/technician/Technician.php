<?php

namespace app\adminapi\controller\technician;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\technician\TechnicianService;

/**
 * @OA\Tag(name="admin-api/technician/Technician", description="管理端师傅管理接口")
 */
class Technician extends BaseAdminController
{
    /**
     * 获取师傅分页列表
     * @OA\Get(
     *     path="/adminapi/technician/technician/pages",
     *     tags={"admin-api/technician/Technician"},
     *     summary="获取师傅分页列表",
     *     description="获取师傅分页数据",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="师傅名称",
     *         @OA\Schema(type="string", example="张师傅")
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         required=false,
     *         description="手机号",
     *         @OA\Schema(type="string", example="13800138000")
     *     ),
     *     @OA\Parameter(
     *         name="apply_status",
     *         in="query",
     *         required=false,
     *         description="申请状态",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="technician_status",
     *         in="query",
     *         required=false,
     *         description="师傅状态",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string", example="user123")
     *     ),
     *     @OA\Parameter(
     *         name="is_enabled",
     *         in="query",
     *         required=false,
     *         description="是否可用",
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
    public function getTechnicianPages()
    {
        $data = array(
            'name' => input('param.name'),
            'mobile' => input('param.mobile'),
            'username' => input('param.username'),
            'apply_status' => input('param.apply_status'),
            'technician_status' => input('param.technician_status'),
            'is_enabled' => input('param.is_enabled'),
        );
        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianValidate.pages');
        
        $result = (new TechnicianService())->getTechnicianPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * 更新师傅信息
     * @OA\Put(
     *     path="/adminapi/technician/technician/{id}",
     *     tags={"admin-api/technician/Technician"},
     *     summary="更新师傅信息",
     *     description="更新指定师傅的详细信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="师傅ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="师傅姓名", example="张师傅"),
     *             @OA\Property(property="mobile", type="string", description="手机号", example="13800138000"),
     *             @OA\Property(property="gender", type="integer", description="性别 0未知 1男 2女", example=1),
     *             @OA\Property(property="certificate_info", type="string", description="证书信息", example="高级技师证"),
     *             @OA\Property(property="work_years", type="integer", description="工作年限", example=5),
     *             @OA\Property(property="description", type="string", description="师傅描述", example="经验丰富的师傅"),
     *             @OA\Property(property="technician_fee_rate", type="number", description="师傅费率", example=30),
     *             @OA\Property(property="technician_status", type="integer", description="师傅状态 0休息 1工作中 2忙碌", example=0),
     *             @OA\Property(property="is_enabled", type="integer", description="是否启用 0否 1是", example=1),
     *             @OA\Property(property="apply_status", type="integer", description="申请状态 0待审核 1已通过 2已拒绝", example=1),
     *             @OA\Property(property="apply_remark", type="string", description="申请备注", example="申请备注"),
     *             @OA\Property(property="audit_remark", type="string", description="审核备注", example="审核通过")
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
    public function updateTechnician($id)
    {
        $data = array(
            'name' => input('param.name'),
            'mobile' => input('param.mobile'),
            'gender' => input('param.gender'),
            'certificate_info' => input('param.certificate_info'),
            'work_years' => input('param.work_years'),
            'description' => input('param.description'),
            'technician_fee_rate' => input('param.technician_fee_rate'),
            'technician_status' => input('param.technician_status'),
            'is_enabled' => input('param.is_enabled'),
            'apply_status' => input('param.apply_status'),
            'apply_remark' => input('param.apply_remark'),
            'audit_remark' => input('param.audit_remark'),
        );
        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianValidate.update');
        
        $result = (new TechnicianService())->updateTechnician($id, $data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * 获取师傅详情
     * @OA\Get(
     *     path="/adminapi/technician/technician/{id}",
     *     tags={"admin-api/technician/Technician"},
     *     summary="获取师傅详情",
     *     description="获取指定师傅的详细信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="师傅ID",
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
    public function getTechnicianInfo($id)
    {
        $data = array(
            'id' => $id,
        );
        
        // 验证参数
        $this->validate(array_merge($data, ['id' => $id]), 'app\adminapi\controller\technician\validate\TechnicianValidate.info');
        
        $result = (new TechnicianService())->getTechnicianInfo($id);
        return ds_json_success('操作成功', $result);
    }


    /**
     * 修改师傅绑定店铺 (RESTful)
     * @OA\Put(
     *     path="/adminapi/technician/technician/{id}/bind-store",
     *     tags={"admin-api/technician/Technician"},
     *     summary="修改师傅绑定店铺",
     *     description="管理员更换师傅绑定的店铺",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="师傅ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"store_id"},
     *             @OA\Property(property="store_id", type="integer", description="店铺ID", example=1)
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
    public function updateTechnicianBindStore($id)
    {
        $data = array(
            'store_id' => input('param.store_id'),
        );

        $this->validate(array_merge($data, ['id' => $id]), 'app\adminapi\controller\technician\validate\TechnicianValidate.updateBindStore');

        (new TechnicianService())->updateTechnicianBindStore($id, $data);

        return ds_json_success('操作成功');
    }

} 