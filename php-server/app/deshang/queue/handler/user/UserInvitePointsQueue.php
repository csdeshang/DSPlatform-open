<?php

namespace app\deshang\queue\handler\user;

use app\deshang\queue\handler\QueueHandlerInterface;
use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\exceptions\CommonException;

/**
 * 用户邀请增加积分队列处理器
 *
 * 说明：
 * - 用户邀请新用户注册成功后，根据系统配置给予邀请人积分奖励
 * - 配置检查在 Listener 层完成，避免不必要的任务入队
 * - 内部调用 DeshangUserPointsService::addPointsForUserInvite() 处理
 */
class UserInvitePointsQueue implements QueueHandlerInterface
{
    /**
     * 执行业务处理
     *
     * @param array $params 必须包含：
     *   - inviter_id: int 邀请人ID
     * @return void
     * @throws CommonException
     */
    public function handle(array $params): void
    {
        $inviter_id = (int)($params['inviter_id'] ?? 0);

        if ($inviter_id <= 0) {
            throw new CommonException('参数缺失或无效: inviter_id');
        }

        // 调用服务类处理积分增加逻辑
        // 注意：此方法会检查配置，如果配置为0，会返回false但不抛异常
        (new DeshangUserPointsService())->addPointsForUserInvite($inviter_id);
    }
}

