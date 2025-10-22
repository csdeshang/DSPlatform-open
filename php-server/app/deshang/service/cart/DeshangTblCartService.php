<?php

namespace app\deshang\service\cart;


use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;


use app\common\dao\cart\TblCartDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\store\TblStoreDao;

use app\deshang\service\goods\DeshangTblGoodsPromotionService;

class DeshangTblCartService  extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblCartDao();
    }


    // 添加商品到购物车
    public function addTblCart(array $data)
    {
        // 调用验证器进行验证
        $this->validate($data, 'app\deshang\service\cart\validate\TblCartValidator.add');


        // 检查商品是否存在
        $goods = (new TblGoodsDao())->getGoodsInfoById($data['goods_id']);


        if (empty($goods)) {
            throw new CommonException('商品不存在');
        }

        // 检查SKU是否存在
        $sku = (new TblGoodsSkuDao())->getGoodsSkuInfo([
            ['id', '=', $data['sku_id']],
            ['goods_id', '=', $data['goods_id']]
        ]);

        if (empty($sku)) {
            throw new CommonException('SKU不存在');
        }

        // 检查库存
        if ($sku['sku_stock'] < $data['quantity']) {
            throw new CommonException('库存不足');
        }

        // 

        // 检查商品是否已在购物车中
        $condition = [];
        $condition[] = ['goods_id', '=', $data['goods_id']];
        $condition[] = ['sku_id', '=', $data['sku_id']];
        $condition[] = ['user_id', '=', $data['user_id']];
        $cartItem = $this->dao->getCartInfo($condition);

        // 检查促销活动
        $promotion = (new DeshangTblGoodsPromotionService())->getTblGoodsPromotionPrice($sku, $data['quantity'], $data['user_id'], $data['promotion_platform'], $data['promotion_type']);


        if ($cartItem) {
            // 如果商品已在购物车中，更新数量
            $cart_data = [
                'quantity' => $cartItem['quantity'] + $data['quantity'],
                'promotion_platform' => $promotion['promotion_platform'], // 添加促销平台
                'promotion_type' => $promotion['promotion_type'], // 添加促销类型
                'promotion_related_id' => $promotion['promotion_related_id'], // 添加促销 ID
                'promotion_price' => $promotion['promotion_price'], //参与活动为活动价格,不参与活动为原价
            ];
            $result = $this->dao->updateCart($condition, $cart_data);
            return $cartItem['id'];
        } else {
            // 如果商品不在购物车中，添加新商品
            $cart_data = [
                'platform' => $goods['platform'],
                'user_id' => $data['user_id'],
                'store_id' => $goods['store_id'],
                'goods_id' => $goods['id'],
                'quantity' => $data['quantity'],
                'sku_id' => $sku['id'], // 添加 SKU ID
                'promotion_platform' => $promotion['promotion_platform'], // 添加促销平台
                'promotion_type' => $promotion['promotion_type'], // 添加促销类型
                'promotion_related_id' => $promotion['promotion_related_id'], // 添加促销 ID
                'promotion_price' => $promotion['promotion_price'], //参与活动为活动价格,不参与活动为原价
            ];
            $result = $this->dao->createCart($cart_data);
            return $result;
        }
        
        
    }



    // 获取购物车列表[最新促销活动价格]
    public function getTblCartList($user_id, $platform, $cart_ids = [] , $store_ids = []): array
    {
        $condition = [];
        $condition[] = ['user_id', '=', $user_id];
        if (isset($platform) && $platform != '') {
            $condition[] = ['platform', '=', $platform];
        }

        if (!empty($cart_ids)) {
            $condition[] = ['id', 'in', $cart_ids];
        }

        if (!empty($store_ids)) {
            $condition[] = ['store_id', 'in', $store_ids];
        }

        // 查询当前用户的购物车商品，并预加载相关的商品和SKU信息
        $cartItems = $this->dao->getWithRelCartList($condition);

        // 商品被删除或SKU被删除 当前购物车则自动清除
        foreach ($cartItems as $key => $item) {
            if (!isset($item['goodsSku']) || !isset($item['goods'])) {
                // 删除购物车商品
                $condition = [];
                $condition[] = ['sku_id', '=', $item['sku_id']];
                $condition[] = ['user_id', '=', $user_id];
                $this->deleteTblCart($condition);
                unset($cartItems[$key]);
            } else {
                // 获取促销活动价格 (数据库存储的数据无效)
                $promotion = (new DeshangTblGoodsPromotionService())->getTblGoodsPromotionPrice($item['goodsSku'], $item['quantity'], $user_id, $item['promotion_platform'], $item['promotion_type']);
                $cartItems[$key]['promotion_platform'] = $promotion['promotion_platform'];
                $cartItems[$key]['promotion_type'] = $promotion['promotion_type'];
                $cartItems[$key]['promotion_related_id'] = $promotion['promotion_related_id'];
                $cartItems[$key]['promotion_price'] = $promotion['promotion_price'];
            }
        }

        return $cartItems;
    }


    // 获取购物车内容 根据店铺ID
    public function getTblCartListGroupedByStore(array $data): array
    {
        // 获取购物车商品列表
        $cartItems = $this->getTblCartList($data['user_id'], $data['platform'], $data['cart_ids'] ?? [] , $data['store_ids'] ?? []);

        // 按 store_id 归类商品
        $groupedItems = [];
        foreach ($cartItems as $item) {
            $store_id = $item['store_id'];
            if (!isset($groupedItems[$store_id])) {
                $groupedItems[$store_id] = [
                    'store_info' => (new TblStoreDao())->getStoreInfoById($store_id), // 查询店铺信息
                    'items' => []
                ];
            }

            $groupedItems[$store_id]['items'][] = $item;
        }


        return $groupedItems;
    }


    // 更新购物车商品数量
    public function updateTblCart($data)
    {
        $this->validate($data, 'app\deshang\service\cart\validate\TblCartValidator.update');

        if (empty($data['cart_id']) || empty($data['quantity'])) {
            throw new CommonException('购物车ID和数量不能为空');
        }

        $cartItem = $this->dao->getCartInfo(
            [
                ['id', '=', $data['cart_id']],
                ['user_id', '=', $data['user_id']]
            ]
        );

        if (empty($cartItem)) {
            throw new CommonException('购物车商品不存在');
        }

        // 检查商品是否存在
        $goods = (new TblGoodsDao())->getGoodsInfoById($cartItem['goods_id']);
        if (empty($goods)) {
            throw new CommonException('商品不存在');
        }

        // 检查SKU是否存在
        $sku = (new TblGoodsSkuDao())->getGoodsSkuInfo([
            ['id', '=', $cartItem['sku_id']],
            ['goods_id', '=', $cartItem['goods_id']]
        ]);
        if (empty($sku)) {
            throw new CommonException('SKU不存在');
        }

        // 检查库存
        if ($sku['sku_stock'] < $data['quantity']) {
            throw new CommonException('库存不足');
        }

        // 检查促销活动
        $promotion = (new DeshangTblGoodsPromotionService())->getTblGoodsPromotionPrice($sku, $data['quantity'], $data['user_id'], $cartItem['promotion_platform'], $cartItem['promotion_type']);


        $cart_data = [
            'quantity' => $data['quantity'],
            'promotion_platform' => $promotion['promotion_platform'], // 添加促销平台
            'promotion_type' => $promotion['promotion_type'], // 添加促销类型
            'promotion_related_id' => $promotion['promotion_related_id'], // 添加促销 ID
            'promotion_price' => $promotion['promotion_price'], //参与活动为活动价格,不参与活动为原价
        ];


        $result = $this->dao->updateCart([['id', '=', $data['cart_id']]], $cart_data);
        return $result;
    }

    // 删除购物车商品
    public function deleteTblCart($data)
    {
        $this->validate($data, 'app\deshang\service\cart\validate\TblCartValidator.delete');

        $condition = [];
        $condition[] = ['id', 'in', $data['cart_ids']];
        $condition[] = ['user_id', '=', $data['user_id']];
        $cartList = $this->dao->getCartList($condition);
        if (empty($cartList)) {
            throw new CommonException('购物车商品不存在');
        }

        // 提取购物车列表中的 id
        $cartIds = array_column($cartList, 'id');

        // 检查是否有未查询到的 id
        $missingIds = array_diff($data['cart_ids'], $cartIds);
        if (!empty($missingIds)) {
            throw new CommonException('部分购物车商品不存在');
        }

        $result = $this->dao->deleteCart([['id', 'in', $cartIds]]);
        return $result;
    }

    // 获取购物车总数
    public function getTblCartTotalQuantity($userId)
    {
        $condition = [];
        $condition[] = ['user_id', '=', $userId];
        $totalQuantity = $this->dao->getCartCount($condition);
        return $totalQuantity;
    }
}
