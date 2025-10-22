<?php


namespace app\listener\order;

use think\facade\Db;
use app\deshang\exceptions\CommonException;

use app\common\enum\order\TblOrderRefundEnum;

use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;


// 订单退款成功
class OrderRefundSuccessListener
{
    public function handle(array $params)
    {
        // 处理统计 商品库存及店铺销量



        // 退货成功 增加商品库存
        $this->handleStatistics($params);
    }

    // 处理统计 商品库存及店铺销量
    public function handleStatistics($params)
    {
        // 是否退货
        $refund_info = $params['refund_info'];
        
        if($refund_info['refund_type'] == TblOrderRefundEnum::TYPE_REFUND_GOODS){
            // 退货成功 增加商品库存
            $condition = [];
            switch($refund_info['order_goods_id']){
                case 0:
                    // 0 全部退款
                    $condition[] = ['order_id', '=', $refund_info['order_id']];
                    break;
                default:
                    // 指定退款
                    $condition[] = ['id', '=', $refund_info['order_goods_id']];
                    break;
            }
            
            $goods_sku_dao = new TblGoodsSkuDao();
            $goods_dao = new TblGoodsDao();
            $store_dao = new TblStoreDao();

            $order_goods_list = (new TblOrderGoodsDao())->getOrderGoodsList($condition);

            foreach($order_goods_list as $order_goods){
                // 退货成功 增加商品库存
                $condition = [];
                $condition[] = ['goods_id', '=', $order_goods['goods_id']];
                $condition[] = ['id', '=', $order_goods['sku_id']];
                $goods_sku_dao->setGoodsSkuInc($condition, 'sku_stock', $order_goods['goods_num']);
    
                // 退货成功 减少商品销量
                $condition = [];
                $condition[] = ['id', '=', $order_goods['goods_id']];
                $goods_dao->setGoodsDec($condition, 'sales_num', $order_goods['goods_num']);
    
                // 退货成功 减少店铺销量
                $condition = [];
                $condition[] = ['id', '=', $order_goods['store_id']];
                $store_dao->setStoreDec($condition, 'sales_num', $order_goods['goods_num']);
                
            }



        }else{
            // 退款 库存不做处理
        }

        
    }



}