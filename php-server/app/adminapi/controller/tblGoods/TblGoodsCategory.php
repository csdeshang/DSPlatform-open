<?php

namespace app\adminapi\controller\tblGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\goods\TblGoodsCategoryService;

/**
 * @OA\Tag(name="admin-api/tblGoods/TblGoodsCategory", description="商品分类管理相关接口")
 */
class TblGoodsCategory extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/category/tree",
     *     tags={"admin-api/tblGoods/TblGoodsCategory"},
     *     summary="获取商品分类树组结构",
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
    public function getTblGoodsCategoryTree()
    {
        $data = array(
            'platform' => input('param.platform'),
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsCategoryValidate.tree');
        

        $result = (new TblGoodsCategoryService())->getTblGoodsCategoryList($data);
        $result = linearToTree($result,'id','pid');

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/category/{id}",
     *     tags={"admin-api/tblGoods/TblGoodsCategory"},
     *     summary="获取商品分类详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取商品分类详情",
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
    public function getTblGoodsCategoryInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $result = (new TblGoodsCategoryService())->getTblGoodsCategoryInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-goods/category",
     *     tags={"admin-api/tblGoods/TblGoodsCategory"},
     *     summary="添加商品分类",
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
    public function createTblGoodsCategory()
    {
        $data = array(
            'platform' => input('param.platform'),
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'image' => input('param.image'),
            'seo_keywords' => input('param.seo_keywords'),
            'seo_description' => input('param.seo_description'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsCategoryValidate.create');


        $result = (new TblGoodsCategoryService())->createTblGoodsCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/tbl-goods/category/{id}",
     *     tags={"admin-api/tblGoods/TblGoodsCategory"},
     *     summary="编辑商品分类",
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
     *             @OA\Property(property="seo_keywords", type="string", example="更新后的关键词"),
     *             @OA\Property(property="seo_description", type="string", example="更新后的描述"),
     *             @OA\Property(property="is_show", type="boolean", example=true, description="是否显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
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
    public function updateTblGoodsCategory($id)
    {
        $data = array(
            'id' => (int) $id,
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'image' => input('param.image'),
            'seo_keywords' => input('param.seo_keywords'),
            'seo_description' => input('param.seo_description'),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsCategoryValidate.update');

        $result = (new TblGoodsCategoryService())->updateTblGoodsCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/tbl-goods/category/{id}",
     *     tags={"admin-api/tblGoods/TblGoodsCategory"},
     *     summary="删除商品分类",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除商品分类",
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
    public function deleteTblGoodsCategory($id)
    {
        $result = (new TblGoodsCategoryService())->deleteTblGoodsCategory((int)$id);
        return ds_json_success('操作成功', $result);
    }
}