<?php

namespace app\listener\user;

use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\service\user\DeshangUserGrowthService;
use app\deshang\service\store\DeshangTblStoreScoreService;
use app\deshang\service\goods\DeshangTblGoodsScoreService;
use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 用户商品评论监听器
 * 
 * 用户商品评论时触发，处理评论奖励（积分、成长值）和评分更新
 */
class UserGoodsCommentListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - goods_ids: array 商品ID数组
     *   - store_id: int 店铺ID
     *   - user_id: int 用户ID
     *   - order_id: int 订单ID
     * @return void
     */
    public function handle(array $params)
    {
        $goods_ids = $params['goods_ids'];
        $store_id = $params['store_id'];
        $user_id = $params['user_id'];
        $order_id = $params['order_id'];

        // 评论获取积分
        $this->goodsCommentGetPoints($user_id, $order_id);

        // 评论获取成长值
        $this->goodsCommentGetGrowth($user_id, $order_id);

        // 更新店铺评分
        $this->updateStoreScore($store_id, $order_id);

        // 更新商品评分
        $this->updateGoodsScore($goods_ids, $order_id);
    }

    /**
     * 评论获取积分
     * 
     * 说明：
     * - 根据系统配置决定是否给予积分奖励
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 使用订单ID，保证唯一性
     * 
     * @param int $user_id 用户ID
     * @param int $order_id 订单ID
     * @return void
     */
    public function goodsCommentGetPoints($user_id, $order_id)
    {
        // 评论获取积分
        $points_review_enabled = sysConfig('points:points_review_enabled');
        if ($points_review_enabled == 1) {
            // 评论获取积分（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserPointsService())->addPointsForUserGoodsComment($user_id);

            // 使用消息队列异步处理积分增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserGoodsCommentPointsQueue',
                    'data' => [
                        'user_id' => $user_id,
                    ],
                    'options' => [
                        'biz_key' => 'UserGoodsCommentPointsQueue_' . $order_id,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }

    /**
     * 评论获取成长值
     * 
     * 说明：
     * - 根据系统配置决定是否给予成长值奖励
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 使用订单ID，保证唯一性
     * 
     * @param int $user_id 用户ID
     * @param int $order_id 订单ID
     * @return void
     */
    public function goodsCommentGetGrowth($user_id, $order_id)
    {
        // 评论获取成长值
        $growth_review_enabled = sysConfig('growth:growth_review_enabled');
        if ($growth_review_enabled == 1) {
            // 评论获取成长值（已改为消息队列异步处理，保留代码方便后期切换）
            // (new DeshangUserGrowthService())->addGrowthForUserGoodsComment($user_id);

            // 使用消息队列异步处理成长值增加
            (new QueueProducer())->enqueue([
                [
                    'type' => 'UserGoodsCommentGrowthQueue',
                    'data' => [
                        'user_id' => $user_id,
                    ],
                    'options' => [
                        'biz_key' => 'UserGoodsCommentGrowthQueue_' . $order_id,
                        'queue_group' => SysTaskQueueEnum::GROUP_USER,
                        'priority' => 1,
                    ],
                ],
            ]);
        }
    }


    /**
     * 更新店铺评分
     * 
     * 说明：
     * - 根据评论重新计算店铺的平均评分
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 使用订单ID，确保每次评论都能触发评分更新
     * 
     * @param int $store_id 店铺ID
     * @param int $order_id 订单ID
     * @return bool
     */
    public function updateStoreScore($store_id, $order_id)
    {
        if (empty($store_id)) {
            return false;
        }

        // 更新店铺评分（已改为消息队列异步处理，保留代码方便后期切换）
        // (new DeshangTblStoreScoreService())->updateStoreScore($store_id);

        // 使用消息队列异步处理店铺评分更新
        // 注意：使用 order_id 作为 biz_key 保证唯一性，确保每次评论都能触发评分更新
        (new QueueProducer())->enqueue([
            [
                'type' => 'StoreScoreUpdateQueue',
                'data' => [
                    'store_id' => $store_id,
                ],
                'options' => [
                    'biz_key' => 'StoreScoreUpdateQueue_' . $order_id,
                    'queue_group' => SysTaskQueueEnum::GROUP_DEFAULT,
                    'priority' => 0,
                ],
            ],
        ]);

        return true;
    }

    /**
     * 更新商品评分
     * 
     * 说明：
     * - 根据评论重新计算商品的平均评分
     * - 使用消息队列异步处理，提高响应速度
     * - biz_key 使用订单ID，确保每次评论都能触发评分更新
     * 
     * @param array $goods_ids 商品ID数组
     * @param int $order_id 订单ID
     * @return bool
     */
    public function updateGoodsScore($goods_ids, $order_id)
    {
        if (empty($goods_ids)) {
            return false;
        }

        // 更新商品评分（已改为消息队列异步处理，保留代码方便后期切换）
        // (new DeshangTblGoodsScoreService())->updateGoodsScore($goods_ids);

        // 使用消息队列异步处理商品评分更新
        // 注意：使用 order_id 作为 biz_key 保证唯一性，确保每次评论都能触发评分更新
        (new QueueProducer())->enqueue([
            [
                'type' => 'GoodsScoreUpdateQueue',
                'data' => [
                    'goods_ids' => $goods_ids,
                ],
                'options' => [
                    'biz_key' => 'GoodsScoreUpdateQueue_' . $order_id,
                    'queue_group' => SysTaskQueueEnum::GROUP_DEFAULT,
                    'priority' => 0,
                ],
            ],
        ]);

        return true;
    }
}
