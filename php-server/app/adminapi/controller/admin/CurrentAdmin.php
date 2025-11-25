<?php

namespace app\adminapi\controller\admin;


use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\admin\CurrentAdminService;

/**
 * @OA\Tag(name="admin-api/admin/CurrentAdmin", description="当前管理员相关接口")
 */
class CurrentAdmin extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/admin/current/info",
     *     summary="获取当前管理员信息",
     *     tags={"admin-api/admin/CurrentAdmin"},
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
    public function getCurrentAdminInfo()
    {
        $data = (new CurrentAdminService())->getCurrentAdminInfo();
        return ds_json_success('操作成功', $data);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/admin/current/menus",
     *     summary="获取当前管理员菜单",
     *     tags={"admin-api/admin/CurrentAdmin"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getCurrentAdminMenus() {
        $data = (new CurrentAdminService())->getCurrentAdminMenus();
        return ds_json_success('操作成功', $data);
    }


    /**
     * @OA\Put(
     *     path="/adminapi/admin/current/password",
     *     summary="修改当前管理员密码",
     *     tags={"admin-api/admin/CurrentAdmin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="old_password", type="string", description="原密码"),
     *             @OA\Property(property="password", type="string", description="新密码"),
     *             @OA\Property(property="confirm_password", type="string", description="确认新密码")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="密码修改成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function editCurrentAdminPassword() {
        $data = [
            'old_password' => input('param.old_password'),
            'password' => input('param.password'),
            'confirm_password' => input('param.confirm_password'),
        ];
        
        // 验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\CurrentAdminValidate.editPassword');
        
        $result = (new CurrentAdminService())->editCurrentAdminPassword($data);
        return ds_json_success('密码修改成功', $result);
    }

}
