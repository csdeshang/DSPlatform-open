<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\system\SysArticleService;

/**
 * @OA\Tag(name="admin-api/system/SysArticle", description="系统文章管理接口")
 */
// 系统文章
class SysArticle extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/article/pages",
     *     summary="获取系统文章分页列表",
     *     tags={"admin-api/system/SysArticle"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         description="文章标题",
     *         @OA\Schema(type="string", example="文章标题")
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
    public function getSysArticlePages()
    {
        $data = array(
            'title' => input('param.title'),
        );

        $result = (new SysArticleService())->getSysArticlePages($data);

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/article/{id}",
     *     summary="获取系统文章详情",
     *     tags={"admin-api/system/SysArticle"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="文章ID",
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
     *     ),

     * )
     */
    public function getSysArticleInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $result = (new SysArticleService())->getSysArticleInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/article",
     *     summary="创建系统文章",
     *     tags={"admin-api/system/SysArticle"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="文章信息",
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="cid", type="integer", description="分类ID", example=1),
     *             @OA\Property(property="title", type="string", description="文章标题", example="文章标题"),
     *             @OA\Property(property="image", type="string", description="文章封面图", example="/uploads/article/cover.jpg"),
     *             @OA\Property(property="content", type="string", description="文章内容", example="文章内容详情"),
     *             @OA\Property(property="publish_author", type="string", description="发布作者", example="管理员"),
     *             @OA\Property(property="publish_time", type="string", description="发布时间", example="2024-01-01 12:00:00"),
     *             @OA\Property(property="virtual_views", type="integer", description="虚拟浏览量", example=100),
     *             @OA\Property(property="is_show", type="integer", description="是否显示", example=1),
     *             @OA\Property(property="sort", type="integer", description="排序", example=255)
     *         )
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
    public function createSysArticle()
    {
        $data = array(

            'cid' => input('param.cid'),
            'title' => input('param.title'),
            'image' => input('param.image'),
            'content' => input('param.content'),
            'publish_author' => input('param.publish_author'),
            'publish_time' => input('param.publish_time'),
            'virtual_views' => input('param.virtual_views'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysArticleValidate.create');


        $result = (new SysArticleService())->createSysArticle($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/system/article/{id}",
     *     summary="更新系统文章",
     *     tags={"admin-api/system/SysArticle"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="文章ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="文章信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="cid", type="integer", description="分类ID", example=1),
     *             @OA\Property(property="title", type="string", description="文章标题", example="更新后的文章标题"),
     *             @OA\Property(property="image", type="string", description="文章封面图", example="/uploads/article/cover.jpg"),
     *             @OA\Property(property="content", type="string", description="文章内容", example="更新后的文章内容"),
     *             @OA\Property(property="publish_author", type="string", description="发布作者", example="管理员"),
     *             @OA\Property(property="publish_time", type="string", description="发布时间", example="2024-01-01 12:00:00"),
     *             @OA\Property(property="virtual_views", type="integer", description="虚拟浏览量", example=100),
     *             @OA\Property(property="is_show", type="integer", description="是否显示", example=1),
     *             @OA\Property(property="sort", type="integer", description="排序", example=255)
     *         )
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
    public function updateSysArticle($id)
    {
        $data = array(
            'cid' => input('param.cid'),
            'title' => input('param.title'),
            'image' => input('param.image'),
            'content' => input('param.content'),
            'publish_author' => input('param.publish_author'),
            'publish_time' => input('param.publish_time'),
            'virtual_views' => input('param.virtual_views'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysArticleValidate.update');

        $result = (new SysArticleService())->updateSysArticle((int)$id,$data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/system/article/batch",
     *     summary="批量删除系统文章",
     *     tags={"admin-api/system/SysArticle"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="要删除的文章ID数组",
     *         @OA\JsonContent(
     *             required={"ids"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"), description="文章ID数组", example={1,2,3})
     *         )
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
    public function deleteBatchSysArticle()
    {
        $data = array(
            'ids' => input('param.ids/a'),
        );
        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\system\validate\SysArticleValidate.deleteBatch');

        $result = (new SysArticleService())->deleteBatchSysArticle($data['ids']);
        return ds_json_success('操作成功', $result);
    }
}