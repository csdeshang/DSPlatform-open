<?php

namespace app\adminapi\controller\admin;

use app\adminapi\service\admin\AdminService;
use app\deshang\base\controller\BaseAdminController;


/**
 * @OA\Info(title="德尚DSPlatform API", version="1.0", description="德尚DSPlatform接口文档")
 * @OA\Tag(name="admin-api/admin/Admin", description="管理员相关接口")
 */
class Admin extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/admin/admins/pages",
     *     summary="获取管理员分页列表",
     *     tags={"admin-api/admin/Admin"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="管理员名称搜索",
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
    public function getAdminPages()
    {
        $data = array(
            'username' => input('param.username', ''),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\Admin.pages');

        $list = (new AdminService())->getAdminPages($data);

        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/admin/admins/{id}",
     *     summary="获取管理员详情",
     *     tags={"admin-api/admin/Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="管理员ID",
     *         required=true,
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
    public function getAdminInfo($id)
    {
        $admin_info = (new AdminService())->getAdminInfo((int)$id);
        return ds_json_success('操作成功', $admin_info);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/admin/admins",
     *     summary="创建管理员",
     *     tags={"admin-api/admin/Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="管理员信息",
     *         @OA\JsonContent(
     *              required={"username", "password", "confirm_password", "role_id"},
     *              @OA\Property(property="username", type="string", example="adminName"),
     *              @OA\Property(property="password", type="string", example="secret"),
     *              @OA\Property(property="confirm_password", type="string", example="secret"),
     *              @OA\Property(property="role_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="添加管理员成功",
     *         @OA\JsonContent(
     *              @OA\Property(property="code", type="integer", example=10000),
     *              @OA\Property(property="msg", type="string", example="添加管理员成功")
     *         )
     *     )
     * )
     */
    public function createAdmin()
    {
        $data = array(
            'username' => input('param.username', ''),
            'password' => input('param.password', ''),
            'confirm_password' => input('param.confirm_password', ''),
            'role_id' => input('param.role_id', 0),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\Admin.create');

        $id = (new AdminService())->createAdmin($data);

        if ($id > 0) {
            return ds_json_success('添加管理员成功');
        }
    }

    /**
     * @OA\Put(
     *     path="/adminapi/admin/admins/{id}",
     *     summary="更新管理员",
     *     tags={"admin-api/admin/Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="管理员ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="更新管理员信息",
     *         @OA\JsonContent(
     *             required={"password", "confirm_password", "role_id"},
     *             @OA\Property(property="password", type="string", example="newpassword"),
     *             @OA\Property(property="confirm_password", type="string", example="newpassword"),
     *             @OA\Property(property="role_id", type="integer", example=2)
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
    public function updateAdmin($id)
    {
        $data = array(
            'id' => $id,
            'password' => input('param.password', ''),
            'confirm_password' => input('param.confirm_password', ''),
            'role_id' => input('param.role_id', 0),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\Admin.update');
        (new AdminService())->updateAdmin($data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/admin/admins/{id}",
     *     summary="删除管理员",
     *     tags={"admin-api/admin/Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="管理员ID",
     *         required=true,
     *         @OA\Schema(type="integer")
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
    public function deleteAdmin($id)
    {
        (new AdminService())->deleteAdmin((int)$id);
        return ds_json_success('操作成功');
    }
}
