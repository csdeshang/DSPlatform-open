<?php

namespace app\adminapi\controller\blogger;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\blogger\BloggerService;

/**
 * @OA\Tag(name="admin-api/blogger/Blogger", description="管理端博主管理接口")
 */
class Blogger extends BaseAdminController
{
    /**
     * 获取博主分页列表
     * @OA\Get(
     *     path="/adminapi/blogger/blogger/pages",
     *     tags={"admin-api/blogger/Blogger"},
     *     summary="获取博主分页列表",
     *     description="获取博主分页数据",
     *     @OA\Parameter(
     *         name="blogger_name",
     *         in="query",
     *         required=false,
     *         description="博主昵称",
     *         @OA\Schema(type="string", example="小美博主")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="integer", example=1001)
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string", example="张三")
     *     ),
     *     @OA\Parameter(
     *         name="verification_status",
     *         in="query",
     *         required=false,
     *         description="认证状态 0未认证 1认证通过 2认证失败",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="verification_type",
     *         in="query",
     *         required=false,
     *         description="认证类型 1个人认证 2企业认证",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="is_live_enabled",
     *         in="query",
     *         required=false,
     *         description="是否开通直播权限",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="is_drama_enabled",
     *         in="query",
     *         required=false,
     *         description="是否开通短剧权限",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="is_enabled",
     *         in="query",
     *         required=false,
     *         description="是否可用",
     *         @OA\Schema(type="integer", example=1)
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
    public function getBloggerPages()
    {
        $data = array(
            'blogger_name' => input('param.blogger_name'),
            'user_id' => input('param.user_id'),
            'username' => input('param.username'),
            'verification_status' => input('param.verification_status'),
            'verification_type' => input('param.verification_type'),
            'is_live_enabled' => input('param.is_live_enabled'),
            'is_drama_enabled' => input('param.is_drama_enabled'),
            'is_enabled' => input('param.is_enabled'),
        );
        
        $result = (new BloggerService())->getBloggerPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * 获取博主详情
     * @OA\Get(
     *     path="/adminapi/blogger/blogger/{id}",
     *     tags={"admin-api/blogger/Blogger"},
     *     summary="获取博主详情",
     *     description="获取指定博主的详细信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="博主ID",
     *         @OA\Schema(type="integer", example=1)
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
    public function getBloggerInfo($id)
    {
        $result = (new BloggerService())->getBloggerInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * 更新博主信息
     * @OA\Put(
     *     path="/adminapi/blogger/blogger/{id}",
     *     tags={"admin-api/blogger/Blogger"},
     *     summary="更新博主信息",
     *     description="更新指定博主的信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="博主ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="博主信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="blogger_name", type="string", example="小美博主", description="博主昵称"),
     *             @OA\Property(property="description", type="string", example="专业美妆博主", description="描述"),
     *             @OA\Property(property="verification_status", type="integer", example=1, description="认证状态"),
     *             @OA\Property(property="verification_type", type="integer", example=1, description="认证类型"),
     *             @OA\Property(property="verification_desc", type="string", example="个人认证", description="认证说明"),
     *             @OA\Property(property="is_live_enabled", type="integer", example=1, description="是否开通直播权限"),
     *             @OA\Property(property="is_drama_enabled", type="integer", example=1, description="是否开通短剧权限"),
     *             @OA\Property(property="is_enabled", type="integer", example=1, description="是否可用")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="更新成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateBlogger($id)
    {
        $data = array(
            'blogger_name' => input('param.blogger_name'),
            'description' => input('param.description'),
            'verification_status' => input('param.verification_status'),
            'verification_type' => input('param.verification_type'),
            'verification_desc' => input('param.verification_desc'),
            'is_live_enabled' => input('param.is_live_enabled'),
            'is_drama_enabled' => input('param.is_drama_enabled'),
            'is_enabled' => input('param.is_enabled'),
        );
        
        $this->validate($data, 'app\adminapi\controller\blogger\validate\BloggerValidate.update');
        
        $result = (new BloggerService())->updateBlogger($id, $data);
        return ds_json_success('更新成功', $result);
    }

    /**
     * 切换博主字段状态
     * @OA\Post(
     *     path="/adminapi/blogger/blogger/toggle-field",
     *     tags={"admin-api/blogger/Blogger"},
     *     summary="切换博主字段状态",
     *     description="切换博主的布尔字段状态",
     *     @OA\RequestBody(
     *         required=true,
     *         description="切换数据",
     *         @OA\JsonContent(
     *             required={"id", "field"},
     *             @OA\Property(property="id", type="integer", example=1, description="博主ID"),
     *             @OA\Property(property="field", type="string", example="is_enabled", description="字段名")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="切换成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="切换成功")
     *         )
     *     )
     * )
     */
    public function toggleBloggerField()
    {
        $data = [
            'id' => input('param.id'),
            'field' => input('param.field')
        ];

        $this->validate($data, 'app\adminapi\controller\blogger\validate\BloggerValidate.toggle');

        $result = (new BloggerService())->toggleBloggerField($data);
        return ds_json_success('切换成功', $result);
    }
}
