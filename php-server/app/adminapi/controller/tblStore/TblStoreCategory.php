<?php

namespace app\adminapi\controller\tblStore;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\store\TblStoreCategoryService;

/**
 * @OA\Tag(name="admin-api/tblStore/TblStoreCategory", description="店铺分类管理相关接口")
 * @OA\PathItem(path="/adminapi/tbl-store/category")
 * @OA\PathItem(path="/adminapi/tbl-store/category/tree")
 * @OA\PathItem(path="/adminapi/tbl-store/category/{id}")
 * @OA\PathItem(path="/adminapi/tbl-store/category")
 * @OA\PathItem(path="/adminapi/tbl-store/category/{id}")
 */
class TblStoreCategory extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/category/tree",
     *     tags={"admin-api/tblStore/TblStoreCategory"},
     *     summary="获取店铺分类树组结构",
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取商品分类树组结构",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblStoreCategoryTree()
    {
        $data = array(
            'platform' => input('param.platform'),
        );

        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStoreCategoryValidate.tree');
        

        $result = (new TblStoreCategoryService())->getTblStoreCategoryList($data);
        $result = linearToTree($result,'id','pid');

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/category/{id}",
     *     tags={"admin-api/tblStore/TblStoreCategory"},
     *     summary="获取店铺分类详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取店铺分类详情",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
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
    public function getTblStoreCategoryInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $result = (new TblStoreCategoryService())->getTblStoreCategoryInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-store/category",
     *     tags={"admin-api/tblStore/TblStoreCategory"},
     *     summary="添加店铺分类",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"platform", "name"},
     *             @OA\Property(property="platform", type="string", example="平台名称"),
     *             @OA\Property(property="pid", type="integer", example=0, description="父级分类ID"),
     *             @OA\Property(property="name", type="string", example="新分类名称"),
     *             @OA\Property(property="image", type="string", example="分类图片链接"),
     *             @OA\Property(property="seo_keywords", type="string", example="关键词"),
     *             @OA\Property(property="seo_description", type="string", example="描述"),
     *             @OA\Property(property="is_show", type="boolean", example=true, description="是否显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
     *             @OA\Property(property="service_fee_rate", type="decimal", example="0.00", description="服务费率"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="成功添加商品分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createTblStoreCategory()
    {
        $data = array(
            'platform' => input('param.platform'),
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'image' => input('param.image'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
            'service_fee_rate' => input('param.service_fee_rate', 0), // 默认服务费率
        );

        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStoreCategoryValidate.create');


        $result = (new TblStoreCategoryService())->createTblStoreCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/tbl-store/category/{id}",
     *     tags={"admin-api/tblStore/TblStoreCategory"},
     *     summary="编辑店铺分类",
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
     *             @OA\Property(property="image", type="string", example="更新后的分类图片链接"),
     *             @OA\Property(property="is_show", type="boolean", example=true, description="是否显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
     *             @OA\Property(property="service_fee_rate", type="decimal", example="0.00", description="服务费率"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功编辑商品分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateTblStoreCategory($id)
    {
        $data = array(
            'id' => (int) $id,
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'image' => input('param.image'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
            'service_fee_rate' => input('param.service_fee_rate', 0), // 默认服务费率
        );

        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStoreCategoryValidate.update');

        $result = (new TblStoreCategoryService())->updateTblStoreCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/tbl-store/category/{id}",
     *     tags={"admin-api/tblStore/TblStoreCategory"},
     *     summary="删除店铺分类",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除店铺分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
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
    public function deleteTblStoreCategory($id)
    {
        $result = (new TblStoreCategoryService())->deleteTblStoreCategory((int)$id);
        return ds_json_success('操作成功', $result);
    }
}