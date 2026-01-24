<?php

namespace app\deshang\queue\handler;

/**
 * 队列任务处理器接口（QueueHandlerInterface）
 * 
 * 职责：
 * - 针对某一类任务（queue_type）实现具体的业务处理逻辑
 * - 由消费者在消费时按类型路由并调用对应处理器（通过 config('queue.handlers') 映射）
 * 
 * 约定：
 * - handle() 实际的业务处理逻辑，内部务必保证幂等（例如订单行悲观锁+标记位）
 * - 任务类型由 config('queue.handlers') 配置映射，无需实现 supports() 方法
 */
interface QueueHandlerInterface
{
    /**
     * 执行业务处理逻辑
     *
     * @param array $params 任务参数（由生产者传入）
     * @return void
     */
    public function handle(array $params): void;
}
