<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\system\SysArticleCategoryService;

/**
 * @OA\Tag(name="admin-api/system/SysArticleCategory", description="系统文章分类管理接口")
 */
// 系统文章分类
class SysArticleCategory extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/article-categories/tree",
     *     summary="获取系统文章分类树形结构",
     *     tags={"admin-api/system/SysArticleCategory"},
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
    public function getSysArticleCategoryTree()
    {
        $data = array(

        );

        $result = (new SysArticleCategoryService())->getSysArticleCategoryList($data);
        $result = linearToTree($result,'id','pid');

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/article-categories/{id}",
     *     summary="获取系统文章分类详情",
     *     tags={"admin-api/system/SysArticleCategory"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="分类ID",
     *         @OA\Schema(type="integer", example=1)
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
    public function getSysArticleCategoryInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $result = (new SysArticleCategoryService())->getSysArticleCategoryInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/article-categories",
     *     summary="创建系统文章分类",
     *     tags={"admin-api/system/SysArticleCategory"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="分类信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", description="父级分类ID", example=0),
     *             @OA\Property(property="name", type="string", description="分类名称", example="分类名称"),
     *             @OA\Property(property="image", type="string", description="分类图片", example="/uploads/category/image.jpg"),
     *             @OA\Property(property="description", type="string", description="分类描述", example="分类描述"),
     *             @OA\Property(property="is_show", type="integer", description="是否显示", example=1),
     *             @OA\Property(property="sort", type="integer", description="排序", example=255)
     *         )
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
    public function createSysArticleCategory()
    {
        $data = array(
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'image' => input('param.image'),
            'description' => input('param.description'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysArticleCategoryValidate.create');


        $result = (new SysArticleCategoryService())->createSysArticleCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/system/article-categories/{id}",
     *     summary="更新系统文章分类",
     *     tags={"admin-api/system/SysArticleCategory"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="分类ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="分类信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="pid", type="integer", description="父级分类ID", example=0),
     *             @OA\Property(property="name", type="string", description="分类名称", example="更新后的分类名称"),
     *             @OA\Property(property="image", type="string", description="分类图片", example="/uploads/category/image.jpg"),
     *             @OA\Property(property="description", type="string", description="分类描述", example="更新后的分类描述"),
     *             @OA\Property(property="is_show", type="integer", description="是否显示", example=1),
     *             @OA\Property(property="sort", type="integer", description="排序", example=255)
     *         )
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
    public function updateSysArticleCategory($id)
    {
        $data = array(
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'image' => input('param.image'),
            'description' => input('param.description'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysArticleCategoryValidate.update');

        $result = (new SysArticleCategoryService())->updateSysArticleCategory($id,$data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/system/article-categories/{id}",
     *     summary="删除系统文章分类",
     *     tags={"admin-api/system/SysArticleCategory"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="分类ID",
     *         @OA\Schema(type="integer", example=1)
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
    public function deleteSysArticleCategory($id)
    {
        $result = (new SysArticleCategoryService())->deleteSysArticleCategory((int)$id);
        return ds_json_success('操作成功', $result);
    }
}