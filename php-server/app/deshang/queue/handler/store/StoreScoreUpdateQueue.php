<?php

namespace app\deshang\queue\handler\store;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\deshang\service\store\DeshangTblStoreScoreService;
use app\deshang\exceptions\CommonException;

/**
 * 店铺评分更新队列处理器
 *
 * 说明：
 * - 异步更新店铺的平均评分
 * - 根据指定时间范围内的评论计算并更新店铺评分
 * - 内部调用 DeshangTblStoreScoreService::updateStoreScore() 处理
 */
class StoreScoreUpdateQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - store_id: int 店铺ID
     *   - time_range: int 可选，时间范围（秒），默认1年
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $store_id = (int)($params['store_id'] ?? 0);
        $time_range = (int)($params['time_range'] ?? 365 * 24 * 3600);

        if ($store_id <= 0) {
            throw new CommonException('参数缺失或无效: store_id');
        }

        // 调用服务类处理店铺评分更新逻辑
        // 注意：如果店铺不存在或没有评论，会返回false但不抛异常
        (new DeshangTblStoreScoreService())->updateStoreScore($store_id, $time_range);
    }
}

