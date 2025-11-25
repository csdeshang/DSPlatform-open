<?php

namespace app\adminapi\controller\video;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\video\VideoCategoryService;

/**
 * @OA\Tag(name="admin-api/video/VideoCategory", description="视频分类管理相关接口")
 */
class VideoCategory extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/video/categories/tree",
     *     tags={"admin-api/video/VideoCategory"},
     *     summary="获取视频分类树组结构",
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=true,
     *         description="分类类型 short/drama/live",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取视频分类树组结构",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getVideoCategoryTree()
    {
        $data = array(
            'type' => input('param.type'),
        );

        $this->validate($data, 'app\adminapi\controller\video\validate\VideoCategoryValidate.tree');

        $result = (new VideoCategoryService())->getVideoCategoryList($data);
        $result = linearToTree($result,'id','pid');

        return ds_json_success('操作成功', $result);
    }


    /**
     * @OA\Get(
     *     path="/adminapi/video/categories/{id}",
     *     tags={"admin-api/video/VideoCategory"},
     *     summary="获取视频分类详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取视频分类详情",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="分类未找到"
     *     )
     * )
     */
    public function getVideoCategoryInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $result = (new VideoCategoryService())->getVideoCategoryInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/video/categories",
     *     tags={"admin-api/video/VideoCategory"},
     *     summary="添加视频分类",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type"},
     *             @OA\Property(property="pid", type="integer", example=0, description="父级分类ID"),
     *             @OA\Property(property="name", type="string", example="新分类名称"),
     *             @OA\Property(property="type", type="string", example="short", description="分类类型 short/drama/live"),
     *             @OA\Property(property="description", type="string", example="分类描述"),
     *             @OA\Property(property="is_show", type="integer", example=1, description="是否显示 0隐藏 1显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="成功添加视频分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createVideoCategory()
    {
        $data = array(
            'pid' => input('param.pid', 0),
            'name' => input('param.name'),
            'type' => input('param.type'),
            'description' => input('param.description', ''),
            'is_show' => input('param.is_show', 1),
            'sort' => input('param.sort', 255),
        );

        $this->validate($data, 'app\adminapi\controller\video\validate\VideoCategoryValidate.create');

        $result = (new VideoCategoryService())->createVideoCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/video/categories/{id}",
     *     tags={"admin-api/video/VideoCategory"},
     *     summary="编辑视频分类",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="pid", type="integer", example=0, description="父级分类ID"),
     *             @OA\Property(property="name", type="string", example="更新后的分类名称"),
     *             @OA\Property(property="type", type="string", example="short", description="分类类型 short/drama/live"),
     *             @OA\Property(property="description", type="string", example="更新后的分类描述"),
     *             @OA\Property(property="is_show", type="integer", example=1, description="是否显示 0隐藏 1显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功编辑视频分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateVideoCategory($id)
    {
        $data = array(
            'id' => (int) $id,
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'type' => input('param.type'),
            'description' => input('param.description'),
            'is_show' => input('param.is_show', 1),
            'sort' => input('param.sort', 255),
        );

        $this->validate($data, 'app\adminapi\controller\video\validate\VideoCategoryValidate.update');

        $result = (new VideoCategoryService())->updateVideoCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/video/categories/{id}",
     *     tags={"admin-api/video/VideoCategory"},
     *     summary="删除视频分类",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除视频分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="分类未找到"
     *     )
     * )
     */
    public function deleteVideoCategory($id)
    {
        $result = (new VideoCategoryService())->deleteVideoCategory((int)$id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Patch(
     *     path="/adminapi/video/categories/{id}/toggle-field",
     *     tags={"admin-api/video/VideoCategory"},
     *     summary="切换视频分类字段状态",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="分类ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"field"},
     *             @OA\Property(property="field", type="string", example="is_show", description="字段名")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="切换成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="切换成功")
     *         )
     *     )
     * )
     */
    public function toggleVideoCategoryField($id)
    {
        $data = [
            'id' => (int)$id,
            'field' => input('param.field')
        ];

        $result = (new VideoCategoryService())->toggleVideoCategoryField($data);
        return ds_json_success('切换成功', $result);
    }
}
