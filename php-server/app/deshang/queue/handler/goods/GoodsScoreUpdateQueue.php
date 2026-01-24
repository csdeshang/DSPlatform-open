<?php

namespace app\deshang\queue\handler\goods;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\deshang\service\goods\DeshangTblGoodsScoreService;
use app\deshang\exceptions\CommonException;

/**
 * 商品评分更新队列处理器
 *
 * 说明：
 * - 异步更新商品的平均评分
 * - 根据指定时间范围内的评论计算并更新商品评分
 * - 内部调用 DeshangTblGoodsScoreService::updateGoodsScore() 处理
 */
class GoodsScoreUpdateQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - goods_ids: array 商品ID数组
     *   - time_range: int 可选，时间范围（秒），默认1年
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $goods_ids = $params['goods_ids'] ?? [];
        $time_range = (int)($params['time_range'] ?? 365 * 24 * 3600);

        if (empty($goods_ids) || !is_array($goods_ids)) {
            throw new CommonException('参数缺失或无效: goods_ids');
        }

        // 调用服务类处理商品评分更新逻辑
        // 注意：如果商品ID为空，会返回false但不抛异常
        (new DeshangTblGoodsScoreService())->updateGoodsScore($goods_ids, $time_range);
    }
}

