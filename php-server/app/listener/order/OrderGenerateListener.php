<?php


namespace app\listener\order;

use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsDao;
use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 订单生成监听器
 * 
 * 订单生成时触发，处理商品库存减少和销量增加
 */
class OrderGenerateListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - order_goods_list: array 订单商品列表
     *   - store_info: array 店铺信息
     *   - user_info: array 用户信息
     * @return void
     */
    public function handle(array $params)
    {
        $order_goods_list = $params['order_goods_list'];
        $store_info = $params['store_info'];
        $user_info = $params['user_info'];

        // 处理统计 商品库存及店铺销量
        $this->handleStatistics($order_goods_list);
    }

    /**
     * 处理统计 商品库存及店铺销量
     * 
     * 说明：
     * - 同步处理商品库存减少（保证库存准确性）
     * - 异步处理商品和店铺销量增加（使用消息队列）
     * 
     * @param array $order_goods_list 订单商品列表
     * @return void
     */
    public function handleStatistics($order_goods_list)
    {
        if (empty($order_goods_list)) {
            return; // 没有商品，不需要处理
        }

        $goods_sku_dao = new TblGoodsSkuDao();
        $goods_dao = new TblGoodsDao();

        // 处理商品库存（同步处理，保证库存准确性）
        foreach ($order_goods_list as $order_goods) {
            // 商品下单 减少规格库存
            $condition = [];
            $condition[] = ['goods_id', '=', $order_goods['goods_id']];
            $condition[] = ['id', '=', $order_goods['sku_id']];
            $goods_sku_dao->setGoodsSkuDec($condition, 'sku_stock', $order_goods['goods_num']);

            //商品下单 减少商品总库存
            $condition = [];
            $condition[] = ['id', '=', $order_goods['goods_id']];
            $goods_dao->setGoodsDec($condition, 'stock_num', $order_goods['goods_num']);

            // 商品下单 增加商品销量（已改为消息队列异步处理，保留代码方便后期切换）
            // $condition = [];
            // $condition[] = ['id', '=', $order_goods['goods_id']];
            // $goods_dao->setGoodsInc($condition, 'sales_num', $order_goods['goods_num']);

            // 商品下单 增加店铺销量（已改为消息队列异步处理，保留代码方便后期切换）
            // $condition = [];
            // $condition[] = ['id', '=', $order_goods['store_id']];
            // $store_dao->setStoreInc($condition, 'sales_num', $order_goods['goods_num']);
        }

        // 使用消息队列异步处理销量增加
        (new QueueProducer())->enqueue([
            [
                'type' => 'OrderGenerateSalesIncQueue',
                'data' => [
                    'order_goods_list' => $order_goods_list,
                ],
                'options' => [
                    'biz_key' => 'OrderGenerateSalesIncQueue_' . ($order_goods_list[0]['order_id'] ?? 0),
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 1,
                ],
            ],
        ]);
    }
}
