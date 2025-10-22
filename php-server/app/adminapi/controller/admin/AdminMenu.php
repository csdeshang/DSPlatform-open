<?php

namespace app\adminapi\controller\admin;

use app\adminapi\service\admin\AdminMenuService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(
 *     name="admin-api/admin/AdminMenu",
 *     description="管理员菜单管理接口"
 * )
 */
class AdminMenu extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/admin/menus/tree",
     *     summary="获取管理员菜单树组结构",
     *     tags={"admin-api/admin/AdminMenu"},
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
    public function getAdminMenuTree()
    {
        $data = array();

        $result = (new AdminMenuService())->getAdminMenuList($data);

        $result = linearToTree($result, 'id', 'pid');

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/admin/menus/options",
     *     summary="获取管理员菜单选项",
     *     tags={"admin-api/admin/AdminMenu"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(

     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="菜单名称"),
     *                     @OA\Property(property="api_url", type="string", example="/api/menu")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getAdminMenuOptions()
    {
        $data = array();

        $result = (new AdminMenuService())->getAdminMenuList($data);
        $result = linearToTree($result, 'id', 'pid');
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/admin/menus/{id}",
     *     summary="获取管理员菜单信息",
     *     tags={"admin-api/admin/AdminMenu"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="菜单ID",
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
    public function getAdminMenuInfo()
    {
        $data = array(
            'id' => input('param.id'),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminMenu.info');

        $result = (new AdminMenuService())->getAdminMenuInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/admin/menus",
     *     summary="创建管理员菜单",
     *     tags={"admin-api/admin/AdminMenu"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="菜单信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", example=0),
     *             @OA\Property(property="api_url", type="string", example="/api/menu"),
     *             @OA\Property(property="component", type="string", example="MenuComponent"),
     *             @OA\Property(property="path", type="string", example="/menu"),
     *             @OA\Property(property="name", type="string", example="菜单名称"),
     *             @OA\Property(property="icon", type="string", example="icon-menu"),
     *             @OA\Property(property="is_show", type="boolean", example=true),
     *             @OA\Property(property="type", type="integer", example=1),
     *             @OA\Property(property="sort", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="菜单创建成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createAdminMenu()
    {

        $data = array(
            'pid' => input('param.pid', 0),
            'path' => input('param.path', ''),
            'component' => input('param.component', ''),
            'title' => input('param.title', ''),
            'api_url' => input('param.api_url', ''),
            'icon' => input('param.icon', ''),
            'is_show' => input('param.is_show', 1),
            'is_enabled' => input('param.is_enabled', 1),
            'type' => input('param.type', 1),
            'sort' => input('param.sort', 0),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminMenu.create');

        $list = (new AdminMenuService())->createAdminMenu($data);

        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/admin/menus/{id}",
     *     summary="更新管理员菜单",
     *     tags={"admin-api/admin/AdminMenu"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="菜单ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="更新菜单信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", example=0),
     *             @OA\Property(property="api_url", type="string", example="/api/menu"),
     *             @OA\Property(property="component", type="string", example="MenuComponent"),
     *             @OA\Property(property="path", type="string", example="/menu"),
     *             @OA\Property(property="name", type="string", example="菜单名称"),
     *             @OA\Property(property="icon", type="string", example="icon-menu"),
     *             @OA\Property(property="is_show", type="boolean", example=true),
     *             @OA\Property(property="type", type="integer", example=1),
     *             @OA\Property(property="sort", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="菜单更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updateAdminMenu($id)
    {
        $data = array(
            'id' => $id,
            'pid' => input('param.pid', 0),
            'api_url' => input('param.api_url', ''),
            'component' => input('param.component', ''),
            'path' => input('param.path', ''),
            'title' => input('param.title', ''),
            'icon' => input('param.icon', ''),
            'is_show' => input('param.is_show', 1),
            'is_enabled' => input('param.is_enabled', 1),
            'type' => input('param.type', 1),
            'sort' => input('param.sort', 0),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\admin\validate\AdminMenu.update');

        $list = (new AdminMenuService())->updateAdminMenu($data);

        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/admin/menus/{id}",
     *     summary="删除管理员菜单",
     *     tags={"admin-api/admin/AdminMenu"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="菜单ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="菜单删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function deleteAdminMenu($id)
    {
        $data = array(
            'id' => (int) $id,
        );

        $result = (new AdminMenuService())->deleteAdminMenu($data);
        return ds_json_success('操作成功');
    }
}
