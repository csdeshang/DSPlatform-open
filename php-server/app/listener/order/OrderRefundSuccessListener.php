<?php


namespace app\listener\order;

use app\common\enum\order\TblOrderRefundEnum;

use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;


/**
 * 订单退款成功监听器
 * 
 * 订单退款成功时触发，处理商品库存恢复和销量减少（仅退货类型）
 */
class OrderRefundSuccessListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - refund_info: array 退款信息
     * @return void
     */
    public function handle(array $params)
    {
        $refund_info = $params['refund_info'];

        // 处理统计 商品库存及店铺销量
        $this->handleStatistics($refund_info);
    }

    /**
     * 处理统计 商品库存及店铺销量
     * 
     * 说明：
     * - 仅处理退货类型（refund_type = TYPE_REFUND_GOODS）
     * - 同步处理商品库存恢复和销量减少（保证数据准确性）
     * - 退款类型不做库存处理
     * 
     * @param array $refund_info 退款信息，包含：
     *   - refund_type: int 退款类型
     *   - order_id: int 订单ID
     *   - order_goods_id: int 订单商品ID（0表示全部退款）
     * @return void
     */
    public function handleStatistics($refund_info)
    {
        // 是否退货


        if ($refund_info['refund_type'] == TblOrderRefundEnum::TYPE_REFUND_GOODS) {
            // 退货成功 增加商品库存
            $condition = [];
            switch ($refund_info['order_goods_id']) {
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

            foreach ($order_goods_list as $order_goods) {
                // 退货成功 增加规格库存
                $condition = [];
                $condition[] = ['goods_id', '=', $order_goods['goods_id']];
                $condition[] = ['id', '=', $order_goods['sku_id']];
                $goods_sku_dao->setGoodsSkuInc($condition, 'sku_stock', $order_goods['goods_num']);

                // 退货成功 增加商品总库存
                $condition = [];
                $condition[] = ['id', '=', $order_goods['goods_id']];
                $goods_dao->setGoodsInc($condition, 'stock_num', $order_goods['goods_num']);

                // 退货成功 减少商品销量
                $condition = [];
                $condition[] = ['id', '=', $order_goods['goods_id']];
                $goods_dao->setGoodsDec($condition, 'sales_num', $order_goods['goods_num']);

                // 退货成功 减少店铺销量
                $condition = [];
                $condition[] = ['id', '=', $order_goods['store_id']];
                $store_dao->setStoreDec($condition, 'sales_num', $order_goods['goods_num']);
            }
        } else {
            // 退款 库存不做处理
        }
    }
}
