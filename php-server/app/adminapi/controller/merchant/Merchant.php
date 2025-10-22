<?php

namespace app\adminapi\controller\merchant;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\merchant\MerchantService;

/**
 * @OA\Tag(
 *     name="admin-api/merchant/Merchant",
 *     description="商户管理接口"
 * )
 */

class Merchant extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/merchant/pages",
     *     summary="获取商户分页列表",
     *     tags={"admin-api/merchant/Merchant"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="商户名称",
     *         @OA\Schema(type="string", example="测试商户")
     *     ),
     *     @OA\Parameter(
     *         name="contact_name",
     *         in="query",
     *         required=false,
     *         description="联系人",
     *         @OA\Schema(type="string", example="张三")
     *     ),
     *     @OA\Parameter(
     *         name="apply_status",
     *         in="query",
     *         required=false,
     *         description="申请状态",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string", example="张三")
     *     ),
     *     @OA\Parameter(
     *         name="contact_phone",
     *         in="query",
     *         required=false,
     *         description="联系电话",
     *         @OA\Schema(type="string", example="13800138000")
     *     ),
     *     @OA\Parameter(
     *         name="balance_min",
     *         in="query",
     *         required=false,
     *         description="最小可用金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_max",
     *         in="query",
     *         required=false,
     *         description="最大可用金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_in_min",
     *         in="query",
     *         required=false,
     *         description="最小总收入",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_in_max",
     *         in="query",
     *         required=false,
     *         description="最大总收入",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_out_min",
     *         in="query",
     *         required=false,
     *         description="最小总支出",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_out_max",
     *         in="query",
     *         required=false,
     *         description="最大总支出",
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
    public function getMerchantPages()
    {
        $data = array(
            'name' => input('param.name'),
            'contact_name' => input('param.contact_name'),
            'apply_status' => input('param.apply_status'),
            'username' => input('param.username'),
            'contact_phone' => input('param.contact_phone'),
            'balance_min' => input('param.balance_min'),
            'balance_max' => input('param.balance_max'),
            'balance_in_min' => input('param.balance_in_min'),
            'balance_in_max' => input('param.balance_in_max'),
            'balance_out_min' => input('param.balance_out_min'),
            'balance_out_max' => input('param.balance_out_max'),
        );

        $result = (new MerchantService())->getMerchantPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/merchant/{id}",
     *     summary="获取商户详细信息",
     *     tags={"admin-api/merchant/Merchant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="商户ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=400, description="商户不存在")
     * )
     */
    public function getMerchantInfo($id)
    {

        $result = (new MerchantService())->getMerchantInfo((int)$id);
        if (empty($result)) {
            return ds_json_error('商户不存在');
        }
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/merchant",
     *     summary="添加商户",
     *     tags={"admin-api/merchant/Merchant"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="商户添加所需信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="商户名称"),
     *             @OA\Property(property="contact_name", type="string", example="联系人"),
     *             @OA\Property(property="contact_phone", type="string", example="13800138000"),
     *             @OA\Property(property="contact_address", type="string", example="联系地址"),
     *             @OA\Property(property="is_enabled", type="integer", example=1, description="状态 0 关闭 1 开启"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="添加成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="添加成功")
     *         )
     *     )
     * )
     */
    public function createMerchant()
    {
        $data = array(
            'user_id' => input('param.user_id'),
            'name' => input('param.name'),
            'contact_name' => input('param.contact_name'),
            'contact_phone' => input('param.contact_phone'),
            'contact_address' => input('param.contact_address'),
            'is_enabled' => input('param.is_enabled', 0),
            'sort' => input('param.sort', 255),
        );

        $this->validate($data, 'app\adminapi\controller\merchant\validate\MerchantValidate.create');


        $result = (new MerchantService())->createMerchant($data);
        return ds_json_success('添加成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/merchant/{id}",
     *     summary="修改商户信息",
     *     tags={"admin-api/merchant/Merchant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="商户修改所需信息",
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="name", type="string", example="商户名称"),
     *             @OA\Property(property="contact_name", type="string", example="联系人"),
     *             @OA\Property(property="contact_phone", type="string", example="13800138000"),
     *             @OA\Property(property="contact_address", type="string", example="联系地址"),
     *             @OA\Property(property="is_enabled", type="integer", example=1, description="状态 0 关闭 1 开启"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="修改成功")
     *         )
     *     )
     * )
     */
    public function updateMerchant($id)
    {
        $data = array(
            'id' => (int)$id,
            'name' => input('param.name'),
            'contact_name' => input('param.contact_name'),
            'contact_phone' => input('param.contact_phone'),
            'contact_address' => input('param.contact_address'),
            'is_enabled' => input('param.is_enabled', 0),
            'sort' => input('param.sort', 255),
            'is_allow_payment' => input('param.is_allow_payment', 0),
            'allowed_store_count' => input('param.allowed_store_count', 0),
            'apply_status' => input('param.apply_status', 0),
            'audit_remark' => input('param.audit_remark', ''),
        );

        $this->validate($data, 'app\adminapi\controller\merchant\validate\MerchantValidate.update');

        $result = (new MerchantService())->updateMerchant($data);
        return ds_json_success('修改成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/merchant/audit/{id}",
     *     summary="审核商户申请",
     *     tags={"admin-api/merchant/Merchant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="审核数据",
     *         @OA\JsonContent(
     *             required={"apply_status"},
     *             @OA\Property(property="apply_status", type="integer", example=1, description="审核状态 1通过 2拒绝"),
     *             @OA\Property(property="audit_remark", type="string", example="审核备注", description="审核备注")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="审核成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="审核成功")
     *         )
     *     )
     * )
     */
    public function auditMerchant($id)
    {
        $data = array(
            'id' => (int)$id,
            'apply_status' => input('param.apply_status'),
            'audit_remark' => input('param.audit_remark', ''),
        );

        $this->validate($data, 'app\adminapi\controller\merchant\validate\MerchantValidate.audit');

        $result = (new MerchantService())->auditMerchant($data);
        return ds_json_success('审核成功', $result);
    }
}