<?php

namespace app\deshang\queue\handler\order;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;
use app\deshang\exceptions\CommonException;

/**
 * 订单关闭销量减少队列处理器
 *
 * 说明：
 * - 订单关闭时，将商品/店铺销量进行回滚（减少）
 * - 参数直接传递 order_goods_list，避免重复查询
 * - 并发安全：事务由 QueueConsumer 层统一管理
 */
class OrderCloseSalesDecQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - order_goods_list: array 订单商品列表（已在 Listener 中查询）
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $orderGoodsList = $params['order_goods_list'] ?? [];
        if (empty($orderGoodsList) || !is_array($orderGoodsList)) {
            throw new CommonException('order_goods_list 缺失或格式错误');
        }

        // 回滚商品与店铺销量
        // 注意：事务由 QueueConsumer 层统一管理
        foreach ($orderGoodsList as $og) {
            $goodsId = (int)($og['goods_id'] ?? 0);
            $storeId = (int)($og['store_id'] ?? 0);
            $goodsNum = (int)($og['goods_num'] ?? 0);

            if ($goodsId <= 0 || $storeId <= 0 || $goodsNum <= 0) {
                continue; // 跳过无效数据
            }

            // 使用 Dao 层自减；如需防负数可在 Dao 内扩展表达式更新
            (new TblGoodsDao())->setGoodsDec(['id' => $goodsId], 'sales_num', $goodsNum);
            (new TblStoreDao())->setStoreDec(['id' => $storeId], 'sales_num', $goodsNum);
        }
    }
}

