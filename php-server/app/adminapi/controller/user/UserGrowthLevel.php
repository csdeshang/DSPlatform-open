<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserGrowthLevelService;

/**
 * @OA\Tag(name="admin-api/user/UserGrowthLevel", description="用户成长值等级管理接口")
 */
class UserGrowthLevel extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/user/growth-level/list",
     *     summary="获取用户成长值等级列表",
     *     tags={"admin-api/user/UserGrowthLevel"},
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
    public function getUserGrowthLevelList(){
        $condition = [];
        $list = (new UserGrowthLevelService())->getUserGrowthLevelList($condition);
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Get(
     *     path="/adminapi/user/growth-level/{id}",
     *     summary="获取用户成长值等级详情",
     *     tags={"admin-api/user/UserGrowthLevel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="成长值等级ID",
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="成长值等级不存在"
     *     )
     * )
     */
    public function getUserGrowthLevelInfo($id)
    {

        $result = (new UserGrowthLevelService())->getUserGrowthLevelInfo((int)$id);
        if (empty($result)) {
            return ds_json_error('成长值等级不存在');
        }
        return ds_json_success('操作成功', $result);
    }


    /**
     * @OA\Post(
     *     path="/adminapi/user/growth-level/create",
     *     summary="创建用户成长值等级",
     *     tags={"admin-api/user/UserGrowthLevel"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="成长值等级信息",
     *         @OA\JsonContent(
     *             required={"level_name", "min_growth"},
     *             @OA\Property(property="level_name", type="string", example="青铜会员", description="等级名称"),
     *             @OA\Property(property="min_growth", type="integer", example=100, description="最低成长值"),
     *             @OA\Property(property="description", type="string", example="青铜会员等级", description="等级描述")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="添加成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="添加成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createUserGrowthLevel()
    {
        $data = array(
            'level_name' => input('param.level_name'),
            'min_growth' => input('param.min_growth'),
            'description' => input('param.description'),
        );

        $this->validate($data, 'app\adminapi\controller\user\validate\UserGrowthLevelValidate.create');


        $result = (new UserGrowthLevelService())->createUserGrowthLevel($data);
        return ds_json_success('添加成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/user/growth-level/{id}",
     *     summary="更新用户成长值等级",
     *     tags={"admin-api/user/UserGrowthLevel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="成长值等级ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="成长值等级信息",
     *         @OA\JsonContent(
     *             required={"level_name", "min_growth"},
     *             @OA\Property(property="level_name", type="string", example="白银会员", description="等级名称"),
     *             @OA\Property(property="min_growth", type="integer", example=500, description="最低成长值"),
     *             @OA\Property(property="description", type="string", example="白银会员等级", description="等级描述")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="修改成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateUserGrowthLevel($id)
    {
        
        $data = array(
            'id' => (int)$id,
            'level_name' => input('param.level_name'),
            'min_growth' => input('param.min_growth'),
            'description' => input('param.description'),
        );

        $this->validate($data, 'app\adminapi\controller\user\validate\UserGrowthLevelValidate.update');


        $result = (new UserGrowthLevelService())->updateUserGrowthLevel((int)$id,$data);
        return ds_json_success('修改成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/user/growth-level/{id}",
     *     summary="删除用户成长值等级",
     *     tags={"admin-api/user/UserGrowthLevel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="成长值等级ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="删除成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function deleteUserGrowthLevel($id)
    {
        $result = (new UserGrowthLevelService())->deleteUserGrowthLevel((int)$id);
        return ds_json_success('删除成功', $result);
    }






}