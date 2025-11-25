<?php

namespace app\adminapi\controller\video;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\video\VideoShortService;


/**
 * @OA\Tag(name="admin-api/video/VideoShort", description="短视频管理接口")
 */
class VideoShort extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/video/shorts/pages",
     *     summary="获取短视频分页列表",
     *     tags={"admin-api/video/VideoShort"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         description="视频标题",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="blogger_id",
     *         in="query",
     *         required=false,
     *         description="博主ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="内容类型",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="audit_status",
     *         in="query",
     *         required=false,
     *         description="审核状态",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_recommend",
     *         in="query",
     *         required=false,
     *         description="是否推荐",
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
    public function getVideoShortPages()
    {
        $data = [
            'title' => input('title', ''),
            'blogger_id' => input('blogger_id', 0),
            'type' => input('type', 0),
            'audit_status' => input('audit_status', ''),
            'is_recommend' => input('is_recommend', ''),
        ];
        
        $result = (new VideoShortService())->getVideoShortPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/video/shorts/{id}",
     *     summary="获取短视频详情",
     *     tags={"admin-api/video/VideoShort"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="短视频ID",
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
    public function getVideoShortInfo($id)
    {
        if (empty($id)) {
            return ds_json_error('参数错误');
        }
        
        $result = (new VideoShortService())->getVideoShortInfo((int)$id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/video/shorts/{id}",
     *     summary="更新短视频信息",
     *     tags={"admin-api/video/VideoShort"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="短视频ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", description="标题"),
     *             @OA\Property(property="description", type="string", description="描述"),
     *             @OA\Property(property="type", type="integer", description="内容类型"),
     *             @OA\Property(property="cid", type="integer", description="分类ID"),
     *             @OA\Property(property="is_recommend", type="integer", description="是否推荐"),
     *             @OA\Property(property="is_top", type="integer", description="是否置顶"),
     *             @OA\Property(property="is_hot", type="integer", description="是否热门")
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
    public function updateVideoShort($id)
    {
        if (empty($id)) {
            return ds_json_error('参数错误');
        }
        
        $data = [
            'title' => input('title', ''),
            'description' => input('description', ''),
            'type' => input('type', 1),
            'cid' => input('cid', 0),
            'is_recommend' => input('is_recommend', 0),
            'is_top' => input('is_top', 0),
            'is_hot' => input('is_hot', 0),
        ];
        
        $this->validate($data, 'app\adminapi\controller\video\validate\VideoShortValidate.update');
        
        $result = (new VideoShortService())->updateVideoShort((int)$id, $data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Patch(
     *     path="/adminapi/video/shorts/{id}/toggle-field",
     *     summary="切换短视频字段状态",
     *     tags={"admin-api/video/VideoShort"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="短视频ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="field", type="string", description="字段名")
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
    public function toggleVideoShortField($id)
    {
        $data = [
            'id' => (int)$id,
            'field' => input('field', ''),
        ];
        
        $this->validate($data, 'app\adminapi\controller\video\validate\VideoShortValidate.toggle');
        
        $result = (new VideoShortService())->toggleVideoShortField($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Patch(
     *     path="/adminapi/video/shorts/{id}/audit",
     *     summary="审核短视频",
     *     tags={"admin-api/video/VideoShort"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="短视频ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="audit_status", type="integer", description="审核状态"),
     *             @OA\Property(property="audit_remark", type="string", description="审核备注")
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
    public function auditVideoShort($id)
    {
        $data = [
            'id' => (int)$id,
            'audit_status' => input('audit_status', 0),
            'audit_remark' => input('audit_remark', ''),
        ];
        
        $this->validate($data, 'app\adminapi\controller\video\validate\VideoShortValidate.audit');
        
        $result = (new VideoShortService())->auditVideoShort($data);
        return ds_json_success('操作成功', $result);
    }
}
