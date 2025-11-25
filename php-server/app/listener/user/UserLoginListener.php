<?php

namespace app\listener\user;

use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\service\user\DeshangUserGrowthService;

use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;
use app\common\dao\system\SysTaskQueueDao;

/**
 * 用户登录监听器
 * 
 * 用户登录时触发，处理每日登录奖励（积分、成长值）
 */
class UserLoginListener
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

        // 登录获取积分
        $this->loginGetPoints($user_id);

        // 登录获取成长值
        $this->loginGetGrowth($user_id);
    }

    /**
     * 登录获取积分
     * 
     * 说明：
     * - 根据系统配置决定是否给予积分奖励
     * - 入队前检查：判断今天是否已领取（避免重复入队）
     * - 入队前检查：判断任务队列中是否已存在相同 biz_key 的任务（避免消息队列未执行导致重复入队）
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 包含日期，保证每日唯一
     * 
     * @param int $user_id 用户ID
     * @return void
     */
    public function loginGetPoints($user_id)
    {
        // 登录获取积分
        $points_login_enabled = sysConfig('points:points_login_enabled');
        if ($points_login_enabled == 1) {
            // 入队前检查：判断今天是否已领取（避免重复入队）
            $pointsService = new DeshangUserPointsService();
            if ($pointsService->hasReceivedLoginPointsToday($user_id)) {
                return; // 今天已领取，跳过
            }

            // 登录获取积分（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserPointsService())->addPointsForUserLogin($user_id);


            
            // 入队前检查：判断任务队列中是否已存在相同 biz_key 的任务
            // 主要目的：避免消息队列未执行（任务可能卡在 PENDING 或 PROCESSING 状态）导致重复入队
            // 场景说明：
            // 1. 用户第一次登录，任务已入队但还未执行（积分记录尚未创建）
            // 2. 用户再次登录时，hasReceivedLoginPointsToday() 返回 false（因为积分记录尚未创建）
            // 3. 如果此时再次入队，会导致 biz_key 重复，触发数据库唯一约束冲突
            // 4. 通过检查任务队列，可以提前发现已存在的任务，避免重复入队
            $bizKey = 'UserLoginPointsQueue_' . $user_id . '_' . date('Ymd');
            $existingTask = (new SysTaskQueueDao())->getSysTaskQueueInfo(
                [['biz_key', '=', $bizKey]],
                'id,status,queue_group,scheduled_at'
            );
            if (!empty($existingTask)) {
                return; // 任务已存在，跳过
            }

            // 使用消息队列异步处理积分增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserLoginPointsQueue',
                    'data' => [
                        'user_id' => $user_id,
                    ],
                    'options' => [
                        'biz_key' => $bizKey,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }

    /**
     * 登录获取成长值
     * 
     * 说明：
     * - 根据系统配置决定是否给予成长值奖励
     * - 入队前检查：判断今天是否已领取（避免重复入队）
     * - 入队前检查：判断任务队列中是否已存在相同 biz_key 的任务（避免消息队列未执行导致重复入队）
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 包含日期，保证每日唯一
     * 
     * @param int $user_id 用户ID
     * @return void
     */
    public function loginGetGrowth($user_id)
    {
        // 登录获取成长值
        $growth_login_enabled = sysConfig('growth:growth_login_enabled');
        if ($growth_login_enabled == 1) {
            // 入队前检查：判断今天是否已领取（避免重复入队）
            $growthService = new DeshangUserGrowthService();
            if ($growthService->hasReceivedLoginGrowthToday($user_id)) {
                return; // 今天已领取，跳过
            }

            // 登录获取成长值（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserGrowthService())->addGrowthForUserLogin($user_id);



            // 入队前检查：判断任务队列中是否已存在相同 biz_key 的任务
            // 主要目的：避免消息队列未执行（任务可能卡在 PENDING 或 PROCESSING 状态）导致重复入队
            // 场景说明：
            // 1. 用户第一次登录，任务已入队但还未执行（成长值记录尚未创建）
            // 2. 用户再次登录时，hasReceivedLoginGrowthToday() 返回 false（因为成长值记录尚未创建）
            // 3. 如果此时再次入队，会导致 biz_key 重复，触发数据库唯一约束冲突
            // 4. 通过检查任务队列，可以提前发现已存在的任务，避免重复入队
            $bizKey = 'UserLoginGrowthQueue_' . $user_id . '_' . date('Ymd');
            $existingTask = (new SysTaskQueueDao())->getSysTaskQueueInfo(
                [['biz_key', '=', $bizKey]],
                'id,status,queue_group,scheduled_at'
            );
            if (!empty($existingTask)) {
                return; // 任务已存在，跳过
            }

            // 使用消息队列异步处理成长值增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserLoginGrowthQueue',
                    'data' => [
                        'user_id' => $user_id,
                    ],
                    'options' => [
                        'biz_key' => $bizKey,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }
}
