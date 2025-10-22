<?php

namespace app\adminapi\controller\attachment;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\attachment\AttachmentCateService;

/**
 * @OA\Tag(
 *     name="admin-api/attachment/AttachmentCate",
 *     description="管理员附件分类管理接口"
 * )
 */
class AttachmentCate extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/attachment/category/list",
     *     summary="获取附件分类列表",
     *     tags={"admin-api/attachment/AttachmentCate"},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="附件类型",
     *         required=false,
     *         @OA\Schema(type="string", example="image")
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
     *                     @OA\Property(property="name", type="string", example="分类名称"),
     *                     @OA\Property(property="pid", type="integer", example=0)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getAttachmentCateList()
    {
        $data = array(
            'type' => input('param.type',''),
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentCate.list');

        $result = (new AttachmentCateService())->getAttachmentCateList($data);
        $result = linearToTree($result, 'id', 'pid');
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/attachment/category",
     *     summary="创建附件分类",
     *     tags={"admin-api/attachment/AttachmentCate"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="附件分类信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", example=0),
     *             @OA\Property(property="type", type="string", example="image"),
     *             @OA\Property(property="name", type="string", example="分类名称"),
     *             @OA\Property(property="sort", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="分类创建成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createAttachmentCate()
    {
        $data = array(
            'pid' =>  (int) input('param.pid',0),
            'type' => input('param.type',''),
            'name' => input('param.name',''),
            'sort' =>  (int) input('param.sort',0),
        );
        //验证器
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentCate.create');

        $list = (new AttachmentCateService())->createAttachmentCate($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/attachment/category/{id}",
     *     summary="更新附件分类",
     *     tags={"admin-api/attachment/AttachmentCate"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="分类ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="更新分类信息",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="更新后的分类名称")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="分类更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updateAttachmentCate($id)
    {
        $data = array(
            'id' =>  (int) $id,
            'name' => input('param.name',''),
        );
        //验证器
        $this->validate($data, 'app\adminapi\controller\attachment\validate\AttachmentCate.update');
        $list = (new AttachmentCateService())->updateAttachmentCate($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/attachment/category/{id}",
     *     summary="删除附件分类",
     *     tags={"admin-api/attachment/AttachmentCate"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="分类ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="分类删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function deleteAttachmentCate($id)
    {
 
        $AttachmentCateService = new AttachmentCateService();
        $result = ($AttachmentCateService)->deleteAttachmentCate((int)$id);
        return ds_json_success('操作成功',$result);
    }
}
