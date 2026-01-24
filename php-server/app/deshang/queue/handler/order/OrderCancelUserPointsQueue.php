<?php

namespace app\deshang\queue\handler\order;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\exceptions\CommonException;

/**
 * 订单取消扣除积分队列处理器
 *
 * 说明：
 * - 订单取消时，扣除订单支付时获得的积分
 * - 如果未找到积分记录，服务层会抛出异常，队列会自动重试
 * - 如果依赖任务未完成，重试时会等待；如果依赖任务失败，重试几次后会标记为失败
 */
class OrderCancelUserPointsQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - order_info: array 订单信息（user_id, id）
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $order_info = $params['order_info'] ?? [];

        if (empty($order_info)) {
            throw new CommonException('参数缺失: order_info');
        }

        // 调用服务层处理积分扣除逻辑
        (new DeshangUserPointsService())->deductPointsForOrderCancel($order_info);
    }
}

