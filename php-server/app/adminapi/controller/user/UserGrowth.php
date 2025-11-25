<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserGrowthService;

/**
 * @OA\Tag(name="admin-api/user/UserGrowth", description="用户成长值管理接口")
 */
class UserGrowth extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/growth-logs/pages",
     *     summary="获取用户成长值日志分页列表",
     *     tags={"admin-api/user/UserGrowth"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="change_type",
     *         in="query",
     *         required=false,
     *         description="变更类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="change_mode",
     *         in="query",
     *         required=false,
     *         description="变更模式",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="related_id",
     *         in="query",
     *         required=false,
     *         description="关联ID（如订单ID、退款ID等）",
     *         @OA\Schema(type="integer", example=123)
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
    public function getUserGrowthLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
            'related_id' => input('param.related_id'),
        );


        
        $list = (new UserGrowthService())->getUserGrowthLogPages($data);
        return ds_json_success('操作成功',$list);

    }

    /**
     * @OA\Get(
     *     path="/adminapi/user/growth-logs/{id}",
     *     summary="获取用户成长值日志详情",
     *     tags={"admin-api/user/UserGrowth"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="成长值日志ID",
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
     *         description="成长值日志未找到"
     *     )
     * )
     */
    public function getUserGrowthLogInfo($id){
        // 验证参数
        $this->validate(['id' => $id], 'app\adminapi\controller\user\validate\UserGrowthValidate.info');

        $info = (new UserGrowthService())->getUserGrowthLogInfo($id);
        return ds_json_success('操作成功',$info);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/user/users/{id}/growth",
     *     summary="修改用户成长值",
     *     tags={"admin-api/user/UserGrowth"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="会员ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="成长值修改信息",
     *         @OA\JsonContent(
     *             required={"change_mode", "change_num"},
     *             @OA\Property(property="change_mode", type="integer", example=1, description="变动模式（1:增加 2:减少）"),
     *             @OA\Property(property="change_num", type="number", format="decimal", example=100.00, description="变动数量")
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
    public function modifyUserGrowth($id){
        $data = array(
            'user_id' => $id,
            'change_mode' => input('param.change_mode'),
            'change_num' => number_format(input('param.change_num'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\user\validate\UserGrowthValidate.modify');

        (new UserGrowthService())->modifyUserGrowth($data);

        return ds_json_success('操作成功');
    }

}