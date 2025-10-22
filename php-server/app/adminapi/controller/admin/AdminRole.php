<?php

namespace app\adminapi\controller\admin;

use app\adminapi\service\admin\AdminRoleService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(
 *     name="admin-api/admin/AdminRole",
 *     description="管理员角色管理接口"
 * )
 */
class AdminRole extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/admin/roles/list",
     *     summary="获取管理员角色列表",
     *     tags={"admin-api/admin/AdminRole"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="管理员"),
     *                     @OA\Property(property="desc", type="string", example="管理员"),
     *                     @OA\Property(property="sort", type="integer", example=1),
     *                     @OA\Property(property="permissions", type="array", 
     *                         @OA\Items(type="string", example="view_admin")
     *                     )
     *                 )

     *             )
     *         )
     *     )
     * )
     */
    public function getAdminRoleList()
    {
        $data = array();

        $result = (new AdminRoleService())->getAdminRoleList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/admin/roles/{id}",
     *     summary="获取管理员角色信息",
     *     tags={"admin-api/admin/AdminRole"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="角色ID",
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
     *     )
     * )
     */
    public function getAdminRoleInfo($id)
    {

        $result = (new AdminRoleService())->getAdminRoleInfo((int)$id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/admin/roles",
     *     summary="创建管理员角色",
     *     tags={"admin-api/admin/AdminRole"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="角色信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="管理员"),
     *             @OA\Property(property="desc", type="string", example="管理员"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *         )
     *     ),

     *     @OA\Response(
     *         response=200,
     *         description="角色创建成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="角色创建成功")
     *         )
     *     )
     * )
     */
    public function createAdminRole()
    {
        $data = array(
            'name' => input('param.name'),
            'desc' => input('param.desc'),
            'sort' => input('param.sort'),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminRole.create');

        $list = (new AdminRoleService())->createAdminRole($data);

        return ds_json_success('创建成功', $list);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/admin/roles/{id}",
     *     summary="更新管理员角色",
     *     tags={"admin-api/admin/AdminRole"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="角色ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="更新角色信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="管理员"),
     *             @OA\Property(property="desc", type="string", example="管理员"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="角色更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="角色更新成功")
     *         )
     *     )
     * )
     */
    public function updateAdminRole($id)
    {
        $data = array(
            'id' => (int) $id,
            'name' => input('param.name'),
            'desc' => input('param.desc'),
            'sort' => input('param.sort'),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminRole.update');

        $result = (new AdminRoleService())->updateAdminRole($data);

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/admin/roles/{id}/rules",
     *     summary="更新角色权限",
     *     tags={"admin-api/admin/AdminRole"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="角色ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="角色权限信息",
     *         @OA\JsonContent(
     *             required={"rules"},
     *             @OA\Property(property="rules", type="array", 
     *                 @OA\Items(type="string", example="view_admin")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="权限更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="权限更新成功")
     *         )
     *     )
     * )
     */
    public function updateAdminRoleRules($id)
    {
        $data = array(
            'id' => (int) $id,
            'rules' => input('param.rules/a'),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminRole.updateRules');

        // 序列化规则数据
        $data['rules'] = serialize($data['rules']);

        $list = (new AdminRoleService())->updateAdminRole($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/admin/roles/{id}",
     *     summary="删除管理员角色",
     *     tags={"admin-api/admin/AdminRole"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="角色ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="角色删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="角色删除成功")
     *         )
     *     )
     * )
     */
    public function deleteAdminRole($id)
    {
        $AdminRoleService = new AdminRoleService();
        $result = ($AdminRoleService)->deleteAdminRole((int)$id);
        return ds_json_success('操作成功', $result);
    }
}
