<?php

namespace app\adminapi\controller\pointsGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\pointsGoods\PointsGoodsService;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;

/**
 * @OA\Tag(
 *     name="admin-api/points-goods/PointsGoods",
 *     description="积分商品管理接口"
 * )
 */
class PointsGoods extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/goods/pages",
     *     summary="获取积分商品分页列表",
     *     tags={"admin-api/points-goods/PointsGoods"},
     *     @OA\Parameter(
     *         name="goods_name",
     *         in="query",
     *         description="商品名称",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="goods_status",
     *         in="query",
     *         description="商品状态 0:下架 1:上架",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1})
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="分类ID",
     *         required=false,
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
    public function getPointsGoodsPages()
    {
        $data = array(
            'goods_name' => input('param.goods_name'),
            'goods_status' => input('param.goods_status'),
            'category_id' => input('param.category_id'),
        );
        // echo 'getPointsGoodsPages';exit;
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsValidate.pages');
        $result = (new PointsGoodsService())->getPointsGoodsPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/points-goods/goods/{id}",
     *     summary="获取积分商品详细信息",
     *     tags={"admin-api/points-goods/PointsGoods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="积分商品ID",
     *         required=true,
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
     *     ),
     *     @OA\Response(response=400, description="积分商品不存在")
     * )
     */
    public function getPointsGoodsInfo($id)
    {
        $data = array('id' => (int)$id);

        echo 'getPointsGoodsInfo';exit;

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsValidate.info');

        $result = (new PointsGoodsService())->getPointsGoodsInfo((int)$id);
        if (empty($result)) {
            return ds_json_error('积分商品不存在');
        }
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/points-goods/goods",
     *     summary="添加积分商品",
     *     tags={"admin-api/points-goods/PointsGoods"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="积分商品添加所需信息",
     *         @OA\JsonContent(
     *             required={"goods_name", "slide_image", "points_price"},
     *             @OA\Property(property="goods_name", type="string", example="积分商品名称"),
     *             @OA\Property(property="goods_advword", type="string", example="商品广告词"),
     *             @OA\Property(property="goods_body", type="string", example="商品详情描述"),
     *             @OA\Property(property="category_id", type="integer", example=1, description="积分商品分类ID"),
     *             @OA\Property(property="slide_image", type="array", @OA\Items(type="string"), example={"轮播图1", "轮播图2"}),
     *             @OA\Property(property="points_price", type="integer", example=100, description="积分价格"),
     *             @OA\Property(property="market_price", type="number", example=10.50, description="市场参考价格"),
     *             @OA\Property(property="stock_num", type="integer", example=100, description="库存数量"),
     *             @OA\Property(property="limit_per_user", type="integer", example=1, description="每人限购数量"),
     *             @OA\Property(property="limit_per_day", type="integer", example=10, description="每日限购数量"),
     *             @OA\Property(property="goods_sort", type="integer", example=255, description="排序权重"),
     *             @OA\Property(property="is_hot", type="integer", example=0, description="是否热门 0:否 1:是"),
     *             @OA\Property(property="is_recommend", type="integer", example=0, description="是否推荐 0:否 1:是"),
     *             @OA\Property(property="is_new", type="integer", example=0, description="是否新品 0:否 1:是"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="添加成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="添加成功")
     *         )
     *     )
     * )
     */
    public function createPointsGoods()
    {
        $data = array(
            'goods_name' => input('param.goods_name'),
            'goods_advword' => input('param.goods_advword'),
            'goods_body' => input('param.goods_body'),
            'category_id' => input('param.category_id', 0),
            'slide_image' => input('param.slide_image/a', []),
            'points_price' => input('param.points_price'),
            'market_price' => input('param.market_price', 0),
            'stock_num' => input('param.stock_num', 0),
            'limit_per_user' => input('param.limit_per_user', 0),
            'limit_per_day' => input('param.limit_per_day', 0),
            'goods_sort' => input('param.goods_sort', 0),
            'is_hot' => input('param.is_hot', 0),
            'is_recommend' => input('param.is_recommend', 0),
            'is_new' => input('param.is_new', 0),
        );

        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsValidate.create');

        $result = (new PointsGoodsService())->createPointsGoods($data);
        return ds_json_success('添加成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/points-goods/goods/{id}",
     *     summary="修改积分商品信息",
     *     tags={"admin-api/points-goods/PointsGoods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="积分商品修改所需信息",
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="goods_name", type="string", example="积分商品名称"),
     *             @OA\Property(property="goods_advword", type="string", example="商品广告词"),
     *             @OA\Property(property="goods_body", type="string", example="商品详情描述"),
     *             @OA\Property(property="goods_status", type="integer", example=1, description="商品状态 0:下架 1:上架"),
     *             @OA\Property(property="category_id", type="integer", example=1, description="积分商品分类ID"),
     *             @OA\Property(property="slide_image", type="array", @OA\Items(type="string"), example={"轮播图1", "轮播图2"}),
     *             @OA\Property(property="points_price", type="integer", example=100, description="积分价格"),
     *             @OA\Property(property="market_price", type="number", example=10.50, description="市场参考价格"),
     *             @OA\Property(property="stock_num", type="integer", example=100, description="库存数量"),
     *             @OA\Property(property="limit_per_user", type="integer", example=1, description="每人限购数量"),
     *             @OA\Property(property="limit_per_day", type="integer", example=10, description="每日限购数量"),
     *             @OA\Property(property="goods_sort", type="integer", example=255, description="排序权重"),
     *             @OA\Property(property="is_hot", type="integer", example=0, description="是否热门 0:否 1:是"),
     *             @OA\Property(property="is_recommend", type="integer", example=0, description="是否推荐 0:否 1:是"),
     *             @OA\Property(property="is_new", type="integer", example=0, description="是否新品 0:否 1:是"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="修改成功")
     *         )
     *     )
     * )
     */
    public function updatePointsGoods($id)
    {


        $data = array(
            'id' => (int)$id,
            'goods_name' => input('param.goods_name'),
            'goods_advword' => input('param.goods_advword'),
            'goods_body' => input('param.goods_body'),
            'goods_status' => input('param.goods_status', 1),
            'category_id' => input('param.category_id', 0),
            'slide_image' => input('param.slide_image/a', []),
            'points_price' => input('param.points_price'),
            'market_price' => input('param.market_price', 0),
            'stock_num' => input('param.stock_num', 0),
            'limit_per_user' => input('param.limit_per_user', 0),
            'limit_per_day' => input('param.limit_per_day', 0),
            'goods_sort' => input('param.goods_sort', 0),
            'is_hot' => input('param.is_hot', 0),
            'is_recommend' => input('param.is_recommend', 0),
            'is_new' => input('param.is_new', 0),
        );


        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsValidate.update');

        $result = (new PointsGoodsService())->updatePointsGoods($data);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::POINTS_GOODS_TAG);

        return ds_json_success('修改成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/points-goods/goods/{id}",
     *     summary="删除积分商品",
     *     tags={"admin-api/points-goods/PointsGoods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="删除成功")
     *         )
     *     )
     * )
     */
    public function deletePointsGoods($id)
    {
        $data = array('id' => (int)$id);
        $this->validate($data, 'app\adminapi\controller\pointsGoods\validate\PointsGoodsValidate.delete');

        $result = (new PointsGoodsService())->deletePointsGoods((int)$id);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::POINTS_GOODS_TAG);

        return ds_json_success('删除成功', $result);
    }


}
