<?php


namespace app\listener\order;

use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;
use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 订单取消监听器
 * 
 * 订单取消时触发，处理商品库存恢复、销量减少、积分扣除、成长值扣除
 */
class OrderCancelListener
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
     * - 异步处理销量减少、积分扣除、成长值扣除（使用消息队列批量入队）
     * 
     * 注意：订单取消与订单关闭处理逻辑相同
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
            // 订单取消 增加goods_sku库存
            $condition = [];
            $condition[] = ['goods_id', '=', $order_goods['goods_id']];
            $condition[] = ['id', '=', $order_goods['sku_id']];
            $goods_sku_dao->setGoodsSkuInc($condition, 'sku_stock', $order_goods['goods_num']);

            // 订单取消 增加Goods表总库存
            $condition = [];
            $condition[] = ['id', '=', $order_goods['goods_id']];
            $goods_dao->setGoodsInc($condition, 'stock_num', $order_goods['goods_num']);

            // 订单取消 减少商品销量（已改为消息队列异步处理，保留代码方便后期切换）
            // $condition = [];
            // $condition[] = ['id', '=', $order_goods['goods_id']];
            // $goods_dao->setGoodsDec($condition, 'sales_num', $order_goods['goods_num']);

            // 订单取消 减少店铺销量（已改为消息队列异步处理，保留代码方便后期切换）
            // $condition = [];
            // $condition[] = ['id', '=', $order_goods['store_id']];
            // $store_dao->setStoreDec($condition, 'sales_num', $order_goods['goods_num']);
        }

        // 使用消息队列批量入队处理（销量减少、积分扣除、成长值扣除）
        (new QueueProducer())->enqueue([
            [
                'type' => 'OrderCancelSalesDecQueue',
                'data' => [
                    'order_goods_list' => $order_goods_list,
                ],
                'options' => [
                    'biz_key' => 'OrderCancelSalesDecQueue_' . $order_info['id'],
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 1,
                ],
            ],
            [
                'type' => 'OrderCancelUserPointsQueue',
                'data' => [
                    'order_info' => $order_info,
                ],
                'options' => [
                    'biz_key' => 'OrderCancelUserPointsQueue_' . $order_info['id'],
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 1,
                ],
            ],
            [
                'type' => 'OrderCancelUserGrowthQueue',
                'data' => [
                    'order_info' => $order_info,
                ],
                'options' => [
                    'biz_key' => 'OrderCancelUserGrowthQueue_' . $order_info['id'],
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 1,
                ],
            ],
        ]);
    }
}
