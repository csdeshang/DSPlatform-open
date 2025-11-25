<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\user\UserBehaviorLogService;

/**
 * @OA\Tag(name="admin-api/user/UserBehaviorLog", description="用户行为日志管理接口")
 */
class UserBehaviorLog extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/user/behavior-logs/pages",
     *     summary="获取用户行为日志分页列表",
     *     tags={"admin-api/user/UserBehaviorLog"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="behavior_type",
     *         in="query",
     *         required=false,
     *         description="行为类型",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="behavior_status",
     *         in="query",
     *         required=false,
     *         description="行为状态",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="risk_level",
     *         in="query",
     *         required=false,
     *         description="风险等级",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="ip_address",
     *         in="query",
     *         required=false,
     *         description="IP地址",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getUserBehaviorLogPages()
    {

        $data = array(
            'username' => input('param.username', ''),
            'behavior_type' => input('param.behavior_type', ''),
            'behavior_status' => input('param.behavior_status', ''),
            'risk_level' => input('param.risk_level', ''),
            'ip_address' => input('param.ip_address', ''),
        );


        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\user\validate\UserBehaviorLogValidate.pages');
        
        $result = (new UserBehaviorLogService())->getUserBehaviorLogPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/user/behavior-logs/{id}",
     *     summary="获取用户行为日志详情",
     *     tags={"admin-api/user/UserBehaviorLog"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="日志ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getUserBehaviorLogInfo($id)
    {   
        // 验证参数
        $this->validate(['id' => $id], 'app\adminapi\controller\user\validate\UserBehaviorLogValidate.info');
        
        $result = (new UserBehaviorLogService())->getUserBehaviorLogInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/user/behavior-logs/{id}",
     *     summary="删除用户行为日志",
     *     tags={"admin-api/user/UserBehaviorLog"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="日志ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="删除成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function deleteUserBehaviorLog($id)
    {   
        // 验证参数
        $this->validate(['id' => $id], 'app\adminapi\controller\user\validate\UserBehaviorLogValidate.delete');
        
        (new UserBehaviorLogService())->deleteUserBehaviorLog($id);
        return ds_json_success('删除成功');
    }
}
