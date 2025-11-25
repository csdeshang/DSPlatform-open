<?php

namespace app\adminapi\controller\video;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\video\VideoCommentService;

/**
 * @OA\Tag(name="admin-api/video/VideoComment", description="视频评论管理接口")
 */
class VideoComment extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/video/comments/pages",
     *     summary="获取视频评论分页列表",
     *     tags={"admin-api/video/VideoComment"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content_type",
     *         in="query",
     *         required=false,
     *         description="内容类型 video_short短视频 video_drama剧集 live直播",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content_id",
     *         in="query",
     *         required=false,
     *         description="内容ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_show",
     *         in="query",
     *         required=false,
     *         description="是否显示 0隐藏 1显示",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_reply",
     *         in="query",
     *         required=false,
     *         description="是否回复 0未回复 1已回复",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
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
    public function getVideoCommentPages()
    {
        $data = [
            'user_id' => input('param.user_id', ''),
            'content_type' => input('param.content_type', ''),
            'content_id' => input('param.content_id', ''),
            'is_show' => input('param.is_show', ''),
            'is_reply' => input('param.is_reply', ''),
            'username' => input('param.username', ''),
        ];

        $result = (new VideoCommentService())->getVideoCommentPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Patch(
     *     path="/adminapi/video/comments/{id}/toggle-field",
     *     summary="切换视频评论字段状态",
     *     tags={"admin-api/video/VideoComment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="评论ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="切换数据",
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
    public function toggleVideoCommentField($id)
    {
        $data = [
            'id' => (int)$id,
            'field' => input('param.field')
        ];

        $this->validate($data, 'app\adminapi\controller\video\validate\VideoCommentValidate.toggle');

        $result = (new VideoCommentService())->toggleVideoCommentField($data);
        return ds_json_success('切换成功', $result);
    }
}
