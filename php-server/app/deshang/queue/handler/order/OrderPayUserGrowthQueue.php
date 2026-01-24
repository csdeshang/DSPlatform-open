<?php

namespace app\deshang\queue\handler\order;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\deshang\service\user\DeshangUserGrowthService;
use app\deshang\exceptions\CommonException;

/**
 * 订单支付增加成长值队列处理器
 *
 * 说明：
 * - 订单支付成功后，根据系统配置给予用户成长值奖励
 * - 配置检查在 Listener 层完成，避免不必要的任务入队
 * - 内部调用 DeshangUserGrowthService::addGrowthForOrderPay() 处理
 */
class OrderPayUserGrowthQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - order_info: array 订单信息（user_id, id, pay_amount等）
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $order_info = $params['order_info'] ?? [];

        if (empty($order_info)) {
            throw new CommonException('参数缺失: order_info');
        }

        // 调用服务类处理成长值增加逻辑
        // 注意：此方法会检查配置，如果配置为0或计算后<=0，会返回false但不抛异常
        (new DeshangUserGrowthService())->addGrowthForOrderPay($order_info);
    }
}

