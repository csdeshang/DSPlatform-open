<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\rider\RiderService;

use app\common\enum\rider\RiderEnum;

/**
 * @OA\Tag(name="admin-api/rider/Rider", description="骑手管理接口")
 */
class Rider extends BaseAdminController
{
   
    /**
     * @OA\Get(
     *     path="/adminapi/rider/riders/pages",
     *     summary="获取骑手分页列表",
     *     tags={"admin-api/rider/Rider"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名搜索",
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
    public function getRiderPages()
    {
        $data = array(
            'username' => input('param.username',''),
        );
        
        
        $result = (new RiderService())->getRiderPages($data);
        return ds_json_success('操作成功', $result);
    }

   
    /**
     * @OA\Get(
     *     path="/adminapi/rider/riders/{id}",
     *     summary="获取骑手详情",
     *     tags={"admin-api/rider/Rider"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="骑手ID",
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
     *     )
     * )
     */
    public function getRiderInfo($id)
    {
        $result = (new RiderService())->getRiderInfo((int)$id);
        return ds_json_success('操作成功', $result);
    }

  
    /**
     * @OA\Post(
     *     path="/adminapi/rider/riders",
     *     summary="创建骑手",
     *     tags={"admin-api/rider/Rider"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="骑手信息",
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1, description="用户ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="添加成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="添加成功")
     *         )
     *     )
     * )
     */
    public function createRider()
    {
        $data = array(
            'user_id' => input('param.user_id',''),
            // 骑手名称
            'name' => '骑手',
            // 骑手手机号
            'mobile' => '',
            // 0休息 1接单 2忙碌
            'status' => RiderEnum::RIDER_STATUS_REST,
            // 骑手余额
            'balance'=>0,
            // 收入总额
            'balance_in'=>0,
            // 支出总额
            'balance_out'=>0,
            //是否可用
            'is_enabled' => 1,
            // 申请状态 0 审核中 1 已通过 2 已拒绝
            'apply_status' => RiderEnum::APPLY_STATUS_APPROVED,
            // 申请备注
            'apply_remark' => '',
            // 审核时间
            'audit_time' => time(),
            // 审核备注
            'audit_remark' => '后台管理员添加',
        );

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderValidate.create');

        $result = (new RiderService())->createRider($data);
        return ds_json_success('添加成功', $result);
    }

 
    /**
     * @OA\Put(
     *     path="/adminapi/rider/riders/{id}",
     *     summary="更新骑手信息",
     *     tags={"admin-api/rider/Rider"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="骑手ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="骑手信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="张三"),
     *             @OA\Property(property="mobile", type="string", example="13800138000"),
     *             @OA\Property(property="status", type="integer", example=1, description="状态：0休息 1接单 2忙碌"),
     *             @OA\Property(property="is_enabled", type="integer", example=1, description="是否启用"),
     *             @OA\Property(property="apply_status", type="integer", example=1, description="申请状态：0审核中 1已通过 2已拒绝"),
     *             @OA\Property(property="audit_remark", type="string", example="审核通过")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="修改成功")
     *         )
     *     )
     * )
     */
    public function updateRider($id)
    {
        $data = array(
            'name' => input('param.name'),
            'mobile' => input('param.mobile'),
            'status' => input('param.status'),
            'is_enabled' => input('param.is_enabled'),
            'apply_status' => input('param.apply_status'),
            'audit_remark' => input('param.audit_remark'),
        );

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderValidate.update');

        $result = (new RiderService())->updateRider((int)$id, $data);
        return ds_json_success('修改成功', $result);
    }

    

}
