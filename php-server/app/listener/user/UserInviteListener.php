<?php

namespace app\listener\user;

use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\service\user\DeshangUserGrowthService;
use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 用户邀请监听器
 * 
 * 用户邀请注册成功时触发，处理邀请奖励（积分、成长值）
 */
class UserInviteListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - inviter_id: int 邀请人ID
     *   - user_id: int 被邀请人ID
     * @return void
     */
    public function handle(array $params)
    {
        $inviter_id = $params['inviter_id'];
        $user_id = $params['user_id'];

        // 邀请获取积分
        $this->inviteGetPoints($inviter_id, $user_id);

        // 邀请获取成长值
        $this->inviteGetGrowth($inviter_id, $user_id);
    }

    /**
     * 邀请获取积分
     * 
     * 说明：
     * - 根据系统配置决定是否给予积分奖励
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 包含邀请人和被邀请人ID，保证唯一性
     * 
     * @param int $inviter_id 邀请人ID
     * @param int $user_id 被邀请人ID
     * @return void
     */
    public function inviteGetPoints($inviter_id, $user_id)
    {
        // 邀请获取积分
        $points_invite_enabled = sysConfig('points:points_invite_enabled');
        if ($points_invite_enabled == 1) {
            // 邀请获取积分（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserPointsService())->addPointsForUserInvite($inviter_id);

            // 使用消息队列异步处理积分增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserInvitePointsQueue',
                    'data' => [
                        'inviter_id' => $inviter_id,
                    ],
                    'options' => [
                        'biz_key' => 'UserInvitePointsQueue_' . $inviter_id . '_' . $user_id,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }

    /**
     * 邀请获取成长值
     * 
     * 说明：
     * - 根据系统配置决定是否给予成长值奖励
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 包含邀请人和被邀请人ID，保证唯一性
     * 
     * @param int $inviter_id 邀请人ID
     * @param int $user_id 被邀请人ID
     * @return void
     */
    public function inviteGetGrowth($inviter_id, $user_id)
    {
        // 邀请获取成长值
        $growth_invite_enabled = sysConfig('growth:growth_invite_enabled');
        if ($growth_invite_enabled == 1) {
            // 邀请获取成长值（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserGrowthService())->addGrowthForUserInvite($inviter_id);

            // 使用消息队列异步处理成长值增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserInviteGrowthQueue',
                    'data' => [
                        'inviter_id' => $inviter_id,
                    ],
                    'options' => [
                        'biz_key' => 'UserInviteGrowthQueue_' . $inviter_id . '_' . $user_id,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }
}
