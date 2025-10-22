<?php


namespace app\listener\order;

use think\facade\Db;
use app\deshang\exceptions\CommonException;

use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;

// 订单生成
class OrderGenerateListener
{
    public function handle(array $params)
    {
        // 处理统计 商品库存及店铺销量
        $this->handleStatistics($params);

        


        

    }

    // 处理统计 商品库存及店铺销量
    public function handleStatistics($params)
    {
        $order_goods_list = $params['order_goods_list'];
        $store_info = $params['store_info'];
        $user_info = $params['user_info'];


        $goods_sku_dao = new TblGoodsSkuDao();
        $goods_dao = new TblGoodsDao();
        $store_dao = new TblStoreDao();

        // 处理商品库存
        foreach($order_goods_list as $order_goods){
            // 减少规格库存
            $condition = [];
            $condition[] = ['goods_id', '=', $order_goods['goods_id']];
            $condition[] = ['id', '=', $order_goods['sku_id']];
            $goods_sku_dao->setGoodsSkuDec($condition, 'sku_stock', $order_goods['goods_num']);

            // 增加商品销量
            $condition = [];
            $condition[] = ['id', '=', $order_goods['goods_id']];
            $goods_dao->setGoodsInc($condition, 'sales_num', $order_goods['goods_num']);

            // 增加店铺销量
            $condition = [];
            $condition[] = ['id', '=', $order_goods['store_id']];
            $store_dao->setStoreInc($condition, 'sales_num', $order_goods['goods_num']);
        }
    }



}