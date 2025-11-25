<?php

namespace app\listener\user;

use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\service\user\DeshangUserGrowthService;

use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 用户注册监听器
 * 
 * 用户注册时触发，处理注册奖励（积分、成长值）
 */
class UserRegisterListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - user_id: int 用户ID
     * @return void
     */
    public function handle(array $params)
    {
        $user_id = $params['user_id'];

        // 注册获取积分
        $this->registerGetPoints($user_id);

        // 注册获取成长值
        $this->registerGetGrowth($user_id);
    }

    /**
     * 注册获取积分
     * 
     * 说明：
     * - 根据系统配置决定是否给予积分奖励
     * - 使用消息队列异步处理，提高响应速度
     * 
     * @param int $user_id 用户ID
     * @return void
     */
    public function registerGetPoints($user_id)
    {
        // 注册获取积分
        $points_register_enabled = sysConfig('points:points_register_enabled');
        if ($points_register_enabled == 1) {
            // 注册获取积分（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserPointsService())->addPointsForUserRegister($user_id);

            // 使用消息队列异步处理积分增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserRegisterPointsQueue',
                    'data' => [
                        'user_id' => $user_id,
                    ],
                    'options' => [
                        'biz_key' => 'UserRegisterPointsQueue_' . $user_id,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }

    /**
     * 注册获取成长值
     * 
     * 说明：
     * - 根据系统配置决定是否给予成长值奖励
     * - 使用消息队列异步处理，提高响应速度
     * 
     * @param int $user_id 用户ID
     * @return void
     */
    public function registerGetGrowth($user_id)
    {
        // 注册获取成长值
        $growth_register_enabled = sysConfig('growth:growth_register_enabled');
        if ($growth_register_enabled == 1) {
            // 注册获取成长值（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserGrowthService())->addGrowthForUserRegister($user_id);

            // 使用消息队列异步处理成长值增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserRegisterGrowthQueue',
                    'data' => [
                        'user_id' => $user_id,
                    ],
                    'options' => [
                        'biz_key' => 'UserRegisterGrowthQueue_' . $user_id,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }
}
