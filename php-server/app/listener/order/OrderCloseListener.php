<?php


namespace app\listener\order;

use think\facade\Db;
use app\deshang\exceptions\CommonException;

use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;


// 订单关闭
class OrderCloseListener
{
    public function handle(array $params)
    {
        // 处理统计 商品库存及店铺销量
        $this->handleStatistics($params);
    }

    // 处理统计 商品库存及店铺销量
    public function handleStatistics($params)
    {
        $order_info = $params['order_info'];

        $goods_sku_dao = new TblGoodsSkuDao();
        $goods_dao = new TblGoodsDao();
        $store_dao = new TblStoreDao();

        // 获取订单商品列表
        $order_goods_list = (new TblOrderGoodsDao())->getOrderGoodsList([['order_id', '=', $order_info['id']]]);

        foreach($order_goods_list as $order_goods){
            // 订单关闭 增加商品库存
            $condition = [];
            $condition[] = ['goods_id', '=', $order_goods['goods_id']];
            $condition[] = ['id', '=', $order_goods['sku_id']];
            $goods_sku_dao->setGoodsSkuInc($condition, 'sku_stock', $order_goods['goods_num']);

            // 订单关闭 减少商品销量
            $condition = [];
            $condition[] = ['id', '=', $order_goods['goods_id']];
            $goods_dao->setGoodsDec($condition, 'sales_num', $order_goods['goods_num']);

            // 订单关闭 减少店铺销量
            $condition = [];
            $condition[] = ['id', '=', $order_goods['store_id']];
            $store_dao->setStoreDec($condition, 'sales_num', $order_goods['goods_num']);
            
        }
        
        
    }



}