<?php


namespace app\listener\order;

use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;
use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 订单关闭监听器
 * 
 * 订单关闭时触发，处理商品库存恢复和销量减少
 */
class OrderCloseListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - order_info: array 订单信息
     * @return void
     */
    public function handle(array $params)
    {
        $order_info = $params['order_info'];
        // 处理统计 商品库存及店铺销量
        $this->handleStatistics($order_info);
    }

    /**
     * 处理统计 商品库存及店铺销量
     * 
     * 说明：
     * - 同步处理商品库存恢复（保证库存准确性）
     * - 异步处理销量减少（使用消息队列）
     * 
     * 注意：订单关闭与订单取消处理逻辑相同
     * 
     * @param array $order_info 订单信息
     * @return void
     */
    public function handleStatistics($order_info)
    {

        $goods_sku_dao = new TblGoodsSkuDao();
        $goods_dao = new TblGoodsDao();
        $store_dao = new TblStoreDao();

        // 获取订单商品列表（只查询一次）
        $order_goods_list = (new TblOrderGoodsDao())->getOrderGoodsList([['order_id', '=', $order_info['id']]]);

        if (empty($order_goods_list)) {
            return; // 没有商品，不需要处理
        }

        foreach ($order_goods_list as $order_goods) {
            // 订单关闭 增加商品库存
            $condition = [];
            $condition[] = ['goods_id', '=', $order_goods['goods_id']];
            $condition[] = ['id', '=', $order_goods['sku_id']];
            $goods_sku_dao->setGoodsSkuInc($condition, 'sku_stock', $order_goods['goods_num']);

            // 订单关闭 增加Goods表总库存
            $condition = [];
            $condition[] = ['id', '=', $order_goods['goods_id']];
            $goods_dao->setGoodsInc($condition, 'stock_num', $order_goods['goods_num']);

            // 订单关闭 减少商品销量（已改为消息队列异步处理，保留代码方便后期切换）
            // $condition = [];
            // $condition[] = ['id', '=', $order_goods['goods_id']];
            // $goods_dao->setGoodsDec($condition, 'sales_num', $order_goods['goods_num']);

            // 订单关闭 减少店铺销量（已改为消息队列异步处理，保留代码方便后期切换）
            // $condition = [];
            // $condition[] = ['id', '=', $order_goods['store_id']];
            // $store_dao->setStoreDec($condition, 'sales_num', $order_goods['goods_num']);
        }

        // 使用消息队列异步处理销量减少
        (new QueueProducer())->enqueue([
            [
                'type' => 'OrderCloseSalesDecQueue',
                'data' => [
                    'order_goods_list' => $order_goods_list,
                ],
                'options' => [
                    'biz_key' => 'OrderCloseSalesDecQueue_' . $order_info['id'],
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 1,
                ],
            ],
        ]);
    }
}
