<?php


namespace app\adminapi\controller\tblStore;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\store\TblStoreService;

/**
 * @OA\Tag(name="admin-api/tblStore/TblStore", description="店铺管理接口")
 */
class TblStore extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/pages",
     *     summary="获取店铺分页列表",
     *     tags={"admin-api/tblStore/TblStore"},
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=false,
     *         description="平台类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="store_name",
     *         in="query",
     *         required=false,
     *         description="店铺名称",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="merchant_id",
     *         in="query",
     *         required=false,
     *         description="商户ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="merchant_name",
     *         in="query",
     *         required=false,
     *         description="商户名称",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="apply_status",
     *         in="query",
     *         required=false,
     *         description="申请状态",
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
    public function getTblStorePage(){
        $data = array(
            'platform'=>input('param.platform'),
            'store_name'=>input('param.store_name'),
            'merchant_id'=>input('param.merchant_id'),
            'merchant_name'=>input('param.merchant_name'),
            'apply_status'=>input('param.apply_status'),
        );
        $result = (new TblStoreService())->getTblStorePage($data);
        return ds_json_success('操作成功',$result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/list",
     *     summary="获取店铺列表",
     *     tags={"admin-api/tblStore/TblStore"},
     *     @OA\Parameter(
     *         name="merchant_id",
     *         in="query",
     *         required=false,
     *         description="商户ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblStoreList(){
        $data = array(
            'merchant_id'=>input('param.merchant_id'),
        );
        $result = (new TblStoreService())->getTblStoreList($data);
        return ds_json_success('操作成功',$result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/info/{id}",
     *     summary="获取店铺详情",
     *     tags={"admin-api/tblStore/TblStore"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="店铺ID",
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
     *     )
     * )
     */
    public function getTblStoreInfo($id){
       
        $result = (new TblStoreService())->getTblStoreInfo($id);
        return ds_json_success('操作成功',$result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-store/create",
     *     summary="创建店铺（已禁用）",
     *     tags={"admin-api/tblStore/TblStore"},
     *     @OA\Response(
     *         response=400,
     *         description="此创建店铺方法不能使用",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="此创建店铺方法不能使用")
     *         )
     *     )
     * )
     */
    public function createTblStore(){
        return ds_json_error('此创建店铺方法不能使用');
    }

    /**
     * @OA\Put(
     *     path="/adminapi/tbl-store/update/{id}",
     *     summary="更新店铺信息",
     *     tags={"admin-api/tblStore/TblStore"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="店铺ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="店铺信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="store_name", type="string", example="我的店铺"),
     *             @OA\Property(property="store_business_status", type="integer", example=1),
     *             @OA\Property(property="service_fee_rate", type="number", example=0.05),
     *             @OA\Property(property="is_enabled", type="integer", example=1),
     *             @OA\Property(property="is_recommend", type="integer", example=0),
     *             @OA\Property(property="apply_status", type="string", example="approved")
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
    public function updateTblStore($id){
        $data = array(
            'id' => $id,
            'store_name'=>input('param.store_name'),
            'store_business_status'=>input('param.store_business_status'),
            'service_fee_rate'=>input('param.service_fee_rate'),
            'is_enabled' => input('param.is_enabled'),
            'is_recommend' => input('param.is_recommend'),
            'sort' => input('param.sort'),
            'store_introduction' => input('param.store_introduction'),
            'contact_name' => input('param.contact_name'),
            'contact_phone' => input('param.contact_phone'),
            'address' => input('param.address'),
            'seo_title' => input('param.seo_title'),
            'seo_keywords' => input('param.seo_keywords'),
            'seo_description' => input('param.seo_description'),

            'apply_status' => input('param.apply_status'),
            'audit_remark' => input('param.audit_remark'),
        );

        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStoreValidate.update');

        $result = (new TblStoreService())->updateTblStore((int)$id,$data);
        return ds_json_success('操作成功',$result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-store/audit",
     *     summary="店铺申请审核",
     *     tags={"admin-api/tblStore/TblStore"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="审核信息",
     *         @OA\JsonContent(
     *              required={"id", "apply_status"},
     *              @OA\Property(property="id", type="integer", example=1, description="店铺ID"),
     *              @OA\Property(property="apply_status", type="integer", example=1, description="审核状态（1:通过 2:拒绝）"),
     *              @OA\Property(property="audit_remark", type="string", example="审核通过", description="审核备注（拒绝时必填）")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *              @OA\Property(property="code", type="integer", example=200),
     *              @OA\Property(property="msg", type="string", example="操作成功"),
     *              @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function auditTblStore(){
        $data = array(
            'id' => input('param.id'),
            'apply_status' => input('param.apply_status'),
            'audit_remark' => input('param.audit_remark'),
        );

        // 验证参数
        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStoreValidate.audit');

        $result = (new TblStoreService())->auditTblStore($data);
        return ds_json_success('操作成功',$result);
    }

}