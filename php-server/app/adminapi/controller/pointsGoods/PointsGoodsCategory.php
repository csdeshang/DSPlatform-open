<?php

namespace app\adminapi\controller\pointsGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\pointsGoods\PointsGoodsCategoryService;

/**
 * @OA\Tag(name="admin-api/points-goods/PointsGoodsCategory", description="积分商品分类管理相关接口")
 */
class PointsGoodsCategory extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/category/tree",
     *     tags={"admin-api/points-goods/PointsGoodsCategory"},
     *     summary="获取积分商品分类树形结构",
     *     @OA\Response(
     *         response=200,
     *         description="成功获取积分商品分类树形结构",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getPointsGoodsCategoryTree()
    {
        $data = array();


        // $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsCategoryValidate.tree');
        
        $result = (new PointsGoodsCategoryService())->getPointsGoodsCategoryList($data);
        $result = linearToTree($result, 'id', 'pid');

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/category/{id}",
     *     tags={"admin-api/points-goods/PointsGoodsCategory"},
     *     summary="获取积分商品分类详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取积分商品分类详情",
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
    public function getPointsGoodsCategoryInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsCategoryValidate.info');
        
        $result = (new PointsGoodsCategoryService())->getPointsGoodsCategoryInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/points-goods/category",
     *     tags={"admin-api/points-goods/PointsGoodsCategory"},
     *     summary="添加积分商品分类",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", example=0, description="父级分类ID"),
     *             @OA\Property(property="name", type="string", example="新分类名称"),
     *             @OA\Property(property="image", type="string", example="分类图片链接"),
     *             @OA\Property(property="is_show", type="integer", example=1, description="是否显示 0:隐藏 1:显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="成功添加积分商品分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createPointsGoodsCategory()
    {
        $data = array(
            'pid' => input('param.pid', 0),
            'name' => input('param.name'),
            'image' => input('param.image', ''),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsCategoryValidate.create');

        $result = (new PointsGoodsCategoryService())->createPointsGoodsCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/points-goods/category/{id}",
     *     tags={"admin-api/points-goods/PointsGoodsCategory"},
     *     summary="编辑积分商品分类",
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
     *             @OA\Property(property="is_show", type="integer", example=1, description="是否显示 0:隐藏 1:显示"),
     *             @OA\Property(property="sort", type="integer", example=255, description="排序"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功编辑积分商品分类",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updatePointsGoodsCategory($id)
    {
        $data = array(
            'id' => (int) $id,
            'pid' => input('param.pid', 0),
            'name' => input('param.name'),
            'image' => input('param.image', ''),
            'is_show' => input('param.is_show', 1), // 默认显示
            'sort' => input('param.sort', 255), // 默认排序
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsCategoryValidate.update');

        $result = (new PointsGoodsCategoryService())->updatePointsGoodsCategory($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/points-goods/category/{id}",
     *     tags={"admin-api/points-goods/PointsGoodsCategory"},
     *     summary="删除积分商品分类",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除积分商品分类",
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
    public function deletePointsGoodsCategory($id)
    {
        $data = array('id' => (int)$id);

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsCategoryValidate.delete');
        
        $result = (new PointsGoodsCategoryService())->deletePointsGoodsCategory((int)$id);
        return ds_json_success('操作成功', $result);
    }
}
