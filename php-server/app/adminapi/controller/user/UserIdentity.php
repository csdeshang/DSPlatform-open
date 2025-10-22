<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserIdentityService;

/**
 * @OA\Tag(name="admin-api/user/UserIdentity", description="用户身份认证管理接口")
 */
class UserIdentity extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/identity/list",
     *     summary="获取用户绑定第三方账号列表",
     *     tags={"admin-api/user/UserIdentity"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
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
    public function getUserIdentityList(){

        $data = array(
            'user_id' => input('param.user_id'),
        );

        
        $list = (new UserIdentityService())->getUserIdentityList($data);
        return ds_json_success('操作成功',$list);

    }



}