<?php

namespace app\adminapi\controller\attachment;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\attachment\AttachmentFileService;

/**
 * @OA\Tag(
 *     name="admin-api/attachment/AttachmentFile",
 *     description="管理员附件文件管理接口"
 * )
 */

/**
 * 管理员附件文件管理
 */
class AttachmentFile extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/attachment/file/pages",
     *     summary="获取附件文件分页",
     *     tags={"admin-api/attachment/AttachmentFile"},
     *     @OA\Parameter(
     *         name="cid",
     *         in="query",
     *         description="分类ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="文件名称",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="文件名称"),
     *                     @OA\Property(property="url", type="string", example="http://example.com/file.jpg")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getAttachmentFilePages(){
        $data = array(
            'cid'=>input('param.cid'),
            'name'=>input('param.name'),
            'type' => input('param.type')
        );

        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentFileValidate.pages');

        $list = (new AttachmentFileService())->getAttachmentFilePages($data);

        return ds_json_success('操作成功', $list);
    }


    /**
     * @OA\Post(
     *     path="/adminapi/attachment/file/image",
     *     summary="上传图片",
     *     tags={"admin-api/attachment/AttachmentFile"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="上传的图片信息",
     *         @OA\JsonContent(
     *             required={"cid"},
     *             @OA\Property(property="cid", type="integer", example=1),
     *             @OA\Property(property="file", type="string", format="binary", description="上传的文件")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="图片上传成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="url", type="string", example="http://example.com/image.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function image(){
        $data = array(
            'cid' => (int)input('param.cid')
        );

        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentFileValidate.image');

        $result = (new AttachmentFileService())->image($data);

        return ds_json_success('上传成功', $result);
    }


    /**
     * @OA\Post(
     *     path="/adminapi/attachment/file/video",
     *     summary="上传视频",
     *     tags={"admin-api/attachment/AttachmentFile"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="上传的视频信息",
     *         @OA\JsonContent(
     *             required={"cid"},
     *             @OA\Property(property="cid", type="integer", example=1),
     *             @OA\Property(property="file", type="string", format="binary", description="上传的视频文件")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="视频上传成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="上传成功"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="url", type="string", example="http://example.com/video.mp4")
     *             )
     *         )
     *     )
     * )
     */
    public function video(){
        $data = array(
            'cid' => (int)input('param.cid')
        );

        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentFileValidate.video');

        $result = (new AttachmentFileService())->video($data);

        return ds_json_success('上传成功', $result);
    }






    /**
     * @OA\Post(
     *     path="/adminapi/attachment/file/update-batch",
     *     summary="批量编辑图片",
     *     tags={"admin-api/attachment/AttachmentFile"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="批量更新图片信息",
     *         @OA\JsonContent(
     *             required={"ids", "cid", "name"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
     *             @OA\Property(property="cid", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="更新后的文件名称")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="图片更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updateBatchAttachmentFile(){
        $data = array(
            'ids' => input('param.ids/a'),
            'cid' => (int)input('param.cid'),
            'name' => input('param.name'),
        );
  

        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentFileValidate.updateBatch');
 

        (new AttachmentFileService())->updateBatchAttachmentFile($data);

        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/attachment/file/del-batch",
     *     summary="批量删除附件文件",
     *     tags={"admin-api/attachment/AttachmentFile"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="要删除的文件ID列表",
     *         @OA\JsonContent(
     *             required={"ids"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"), example={1, 2, 3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="文件删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function deleteBatchAttachmentFile(){
        $data = array(
            'ids' => input('param.ids/a'),
        );


        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentFileValidate.deleteBatch');

        $result = (new AttachmentFileService())->deleteBatchAttachmentFile($data['ids']);

        return ds_json_success('删除成功', $result);
    }

}