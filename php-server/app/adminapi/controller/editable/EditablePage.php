<?php

namespace app\adminapi\controller\editable;


use app\deshang\base\controller\BaseAdminController;


use app\adminapi\service\editable\EditablePageService;


/**
 * @OA\Tag(
 *     name="admin-api/editable/EditablePage",
 *     description="可编辑页面管理接口"
 * )
 */
class EditablePage extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/editable/editables/pages",
     *     summary="获取可编辑页面列表",
     *     tags={"admin-api/editable/EditablePage"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         description="页面标题",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=false,
     *         description="平台类型",
     *         @OA\Schema(type="string")
     *     ),
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
    public function getEditablePages()
    {
        $data = array(
            'title' => input('param.title'),
            'platform' => input('param.platform'),
        );


        $list = (new EditablePageService())->getEditablePages($data);

        return ds_json_success('操作成功', $list);
    }


    /**
     * @OA\Get(
     *     path="/adminapi/editable/editables/{id}",
     *     summary="获取单个可编辑页面信息",
     *     tags={"admin-api/editable/EditablePage"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="页面ID",
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
    public function getEditablePageInfo($id)
    {
        $info = (new EditablePageService())->getEditablePageInfo((int) $id);
        return ds_json_success('操作成功', $info);
    }



    /**
     * @OA\Post(
     *     path="/adminapi/editable/editables",
     *     summary="创建可编辑页面",
     *     tags={"admin-api/editable/EditablePage"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="页面信息",
     *         @OA\JsonContent(
     *             required={"title", "platform", "type"},
     *             @OA\Property(property="title", type="string", example="首页", description="页面标题"),
     *             @OA\Property(property="platform", type="string", example="h5", description="平台类型"),
     *             @OA\Property(property="type", type="string", example="home", description="页面类型")
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
    public function createEditablePage()
    {
        $data = array(
            'title' => input('param.title', ''),
            'platform' => input('param.platform', ''),
            'type' => input('param.type', ''),
            'is_default' => 0,
        );

        //验证器
        $this->validate($data, 'app\adminapi\controller\editable\validate\EditablePageValidate.create');


        $result = (new EditablePageService())->createEditablePage($data);
        return ds_json_success('操作成功', $result);
    }


    /**
     * @OA\Put(
     *     path="/adminapi/editable/editables/{id}",
     *     summary="更新可编辑页面",
     *     tags={"admin-api/editable/EditablePage"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="页面ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="页面更新信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="首页", description="页面标题"),
     *             @OA\Property(property="page_config", type="string", example="{}", description="页面配置JSON字符串")
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
    public function updateEditablePage($id)
    {
        $data = array(
            'title' => input('param.title', ''),
            'page_config' => input('param.page_config', ''),
        );
        $result = (new EditablePageService())->updateEditablePage($id, $data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/editable/editables/{id}",
     *     summary="删除可编辑页面",
     *     tags={"admin-api/editable/EditablePage"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="页面ID",
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
    public function deleteEditablePage($id)
    {
        $result = (new EditablePageService())->deleteEditablePage($id);
        return ds_json_success('操作成功', $result);
    }



}