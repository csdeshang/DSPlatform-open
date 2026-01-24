<?php

/**
 * 队列系统配置文件
 * 
 * 说明：
 * - 本文件定义了队列系统的核心配置
 * - 包括任务处理器映射、队列驱动类型等
 * - 配置文件位置：app/deshang/queue/config.php
 * - 通过 config('queue.xxx') 访问配置项
 */

return [
    /**
     * 任务处理器映射配置
     * 
     * 格式：'任务类型' => '处理器类的完全限定名'
     * 
     * 说明：
     * - 任务类型（queue_type）：由 QueueProducer::enqueue() 的第一个参数指定
     * - 处理器类：必须实现 \app\deshang\queue\handler\QueueHandlerInterface 接口
     * - 消费者（QueueConsumer）会根据 queue_type 路由到对应的处理器
     * 
     * 示例：
     * - 入队时：QueueProducer::enqueue('OrderGenerateSalesIncQueue', $data, $options)
     * - 消费时：自动路由到 OrderGenerateSalesIncQueue 处理器
     */
    'handlers' => [
        // 订单相关队列处理器
        'OrderGenerateSalesIncQueue'     => \app\deshang\queue\handler\order\OrderGenerateSalesIncQueue::class,     // 订单生成销量增加
        'OrderCancelSalesDecQueue'       => \app\deshang\queue\handler\order\OrderCancelSalesDecQueue::class,       // 订单取消销量减少
        'OrderCloseSalesDecQueue'        => \app\deshang\queue\handler\order\OrderCloseSalesDecQueue::class,        // 订单关闭销量减少
        'OrderPayUserPointsQueue'        => \app\deshang\queue\handler\order\OrderPayUserPointsQueue::class,        // 订单支付增加积分
        'OrderCancelUserPointsQueue'     => \app\deshang\queue\handler\order\OrderCancelUserPointsQueue::class,     // 订单取消扣除积分
        'OrderPayUserGrowthQueue'        => \app\deshang\queue\handler\order\OrderPayUserGrowthQueue::class,        // 订单支付增加成长值
        'OrderCancelUserGrowthQueue'     => \app\deshang\queue\handler\order\OrderCancelUserGrowthQueue::class,     // 订单取消扣除成长值

        // 用户登录队列处理器
        'UserLoginPointsQueue'           => \app\deshang\queue\handler\user\UserLoginPointsQueue::class,           // 用户登录增加积分
        'UserLoginGrowthQueue'           => \app\deshang\queue\handler\user\UserLoginGrowthQueue::class,           // 用户登录增加成长值

        // 用户注册队列处理器
        'UserRegisterPointsQueue'        => \app\deshang\queue\handler\user\UserRegisterPointsQueue::class,        // 用户注册增加积分
        'UserRegisterGrowthQueue'        => \app\deshang\queue\handler\user\UserRegisterGrowthQueue::class,        // 用户注册增加成长值

        // 用户邀请队列处理器
        'UserInvitePointsQueue'          => \app\deshang\queue\handler\user\UserInvitePointsQueue::class,          // 用户邀请增加积分
        'UserInviteGrowthQueue'          => \app\deshang\queue\handler\user\UserInviteGrowthQueue::class,          // 用户邀请增加成长值

        // 用户商品评论队列处理器
        'UserGoodsCommentPointsQueue'    => \app\deshang\queue\handler\user\UserGoodsCommentPointsQueue::class,    // 用户商品评论增加积分
        'UserGoodsCommentGrowthQueue'    => \app\deshang\queue\handler\user\UserGoodsCommentGrowthQueue::class,    // 用户商品评论增加成长值

        // 评分更新队列处理器
        'StoreScoreUpdateQueue'          => \app\deshang\queue\handler\store\StoreScoreUpdateQueue::class,          // 店铺评分更新
        'GoodsScoreUpdateQueue'          => \app\deshang\queue\handler\goods\GoodsScoreUpdateQueue::class,          // 商品评分更新

    ],

    /**
     * 队列驱动类型
     * 
     * 可选值：
     * - 'db': 数据库驱动（默认，使用 sys_task_queue 表）
     * - 'redis_list': Redis 列表驱动（LPUSH/RPOP）
     * - 'redis_stream': Redis Streams 驱动（XADD/XREAD）
     * - 'rabbitmq': RabbitMQ 驱动
     * 
     * 说明：
     * - 所有任务都会先写入 sys_task_queue 表（用于审计和状态追踪）
     * - 非 db 驱动会额外推送到对应的队列系统（Redis/RabbitMQ）
     * - 消费者统一从配置的驱动中获取任务
     * 
     * 性能对比：
     * - db: 稳定可靠，适合中小规模，性能中等
     * - redis_list: 高性能，适合高并发场景，但重试机制需要额外实现
     * - redis_stream: 高性能，支持消费组和 ACK，功能最完善
     * - rabbitmq: 企业级解决方案，功能最全，但需要额外部署
     */
    'driver' => 'db',
];
