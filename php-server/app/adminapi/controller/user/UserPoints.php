<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserPointsService;

/**
 * @OA\Tag(name="admin-api/user/UserPoints", description="用户积分管理接口")
 */
class UserPoints extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/points-logs/pages",
     *     summary="获取用户积分日志分页列表",
     *     tags={"admin-api/user/UserPoints"},
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
    public function getUserPointsLogPages(){

        $data = array(
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
            'related_id' => input('param.related_id'),
        );

        
        $list = (new UserPointsService())->getUserPointsLogPages($data);
        return ds_json_success('操作成功',$list);

    }

    /**
     * @OA\Get(
     *     path="/adminapi/user/points-logs/{id}",
     *     summary="获取用户积分日志详情",
     *     tags={"admin-api/user/UserPoints"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="积分日志ID",
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
     *         description="积分日志未找到"
     *     )
     * )
     */
    public function getUserPointsLogInfo($id){
        // 验证参数
        $this->validate(['id' => $id], 'app\adminapi\controller\user\validate\UserPointsValidate.info');

        $info = (new UserPointsService())->getUserPointsLogInfo($id);
        return ds_json_success('操作成功',$info);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/user/users/{id}/points",
     *     summary="修改用户积分",
     *     tags={"admin-api/user/UserPoints"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="会员ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="积分修改信息",
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
    public function modifyUserPoints($id){
        $data = array(
            'user_id' => $id,
            'change_mode' => input('param.change_mode'),
            'change_num' => number_format(input('param.change_num'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\user\validate\UserPointsValidate.modify');

        (new UserPointsService())->modifyUserPoints($data);

        return ds_json_success('操作成功');
    }

}