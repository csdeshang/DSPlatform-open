<?php

namespace app\deshang\queue\handler\user;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\exceptions\CommonException;

/**
 * 用户登录增加积分队列处理器
 *
 * 说明：
 * - 用户登录时，根据系统配置给予用户积分奖励（每天首次登录）
 * - 配置检查在 Listener 层完成，避免不必要的任务入队
 * - 内部调用 DeshangUserPointsService::addPointsForUserLogin() 处理
 * - 如果今天已领取，会静默跳过（返回false但不抛异常）
 */
class UserLoginPointsQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - user_id: int 用户ID
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $user_id = (int)($params['user_id'] ?? 0);

        if ($user_id <= 0) {
            throw new CommonException('参数缺失或无效: user_id');
        }

        // 调用服务类处理积分增加逻辑
        // 注意：此方法会检查配置和今天是否已领取，如果配置为0或今天已领取，会返回false但不抛异常
        (new DeshangUserPointsService())->addPointsForUserLogin($user_id);
    }
}

