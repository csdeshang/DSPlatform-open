<?php

namespace app\deshang\queue\core;

use app\deshang\queue\driver\RedisListDriver;
use app\deshang\queue\driver\RedisStreamDriver;
use app\deshang\queue\driver\RabbitMqDriver;
use app\deshang\queue\driver\DbDriver;
use app\common\dao\system\SysTaskQueueDao;
use app\common\model\system\SysTaskQueueModel;
use app\deshang\exceptions\CommonException;
use app\common\enum\system\SysTaskQueueEnum;

/**
 * 统一生产者（业务入队入口）
 * 
 * 职责：
 * - 封装底层驱动（默认 db），统一入队接口
 * - 统一使用批量入队方式，单个任务也传入数组（包含一个元素）
 * - 业务侧仅需传入任务类型、参数、幂等键、优先级、延迟时间与分组
 * 
 * 行为：
 * - 全部入库：先写入 sys_task_queue（若提供 biz_key 则按 biz_key 防重复）
 * - 非 DB 驱动：仅在本次是"首次入库"时再推送队列，避免重复投递
 * 
 * 使用示例：
 * // 单个任务
 * $producer->enqueue([
 *     [
 *         'type' => 'OrderPayUserPointsQueue',
 *         'data' => ['order_info' => $orderInfo],
     *         'options' => ['biz_key' => 'OrderPayUserPointsQueue_123', 'queue_group' => 'order', 'max_retries' => 5, ...]
 *     ]
 * ]);
 * 
 * // 批量任务
 * $producer->enqueue([
 *     ['type' => 'OrderCancelSalesDecQueue', 'data' => [...], 'options' => [...]],
 *     ['type' => 'OrderCancelUserPointsQueue', 'data' => [...], 'options' => [...]],
 * ]);
 */
class QueueProducer
{
    private $driver;

    public function __construct($driver = null)
    {
        if ($driver) {
            $this->driver = $driver;
            return;
        }
        // 根据配置选择驱动（默认 db）
        $driverName = (string) (config('queue.driver') ?: 'db');
        switch ($driverName) {
            case 'redis_list':
                $this->driver = new RedisListDriver();
                break;
            case 'redis_stream':
                $this->driver = new RedisStreamDriver();
                break;
            case 'rabbitmq':
                $this->driver = new RabbitMqDriver();
                break;
            case 'db':
            default:
                $this->driver = new DbDriver();
                break;
        }
    }

    /**
     * 批量入队任务（统一接口）
     * 
     * 说明：
     * - 单个任务时也传入数组（包含一个元素）
     * - 批量任务时传入多个元素的数组
     * - 统一使用批量插入，简化代码维护
     *
     * @param array $tasks 任务数组，每个元素包含：
     *   - type: string 任务类型（必填）
     *   - data: array 任务数据（必填）
     *   - options: array 选项（必填 biz_key，可选 delay_sec, priority, queue_group, max_retries）
     * @return void
     * @throws CommonException
     */
    public function enqueue(array $tasks): void
    {
        if (empty($tasks)) {
            return;
        }

        $driverName = (string) (config('queue.driver') ?: 'db');
        $dao = new SysTaskQueueDao();
        $model = new SysTaskQueueModel();
        $now = time();

        $insertData = [];
        $payloads = [];

        // 1. 准备批量数据
        foreach ($tasks as $index => $task) {
            if (!is_array($task)) {
                throw new CommonException("任务 #{$index}: 必须是数组格式");
            }

            $type = $task['type'] ?? '';
            $data = $task['data'] ?? [];
            $options = $task['options'] ?? [];

            if (empty($type)) {
                throw new CommonException("任务 #{$index}: type 必填");
            }

            if (empty($data) || !is_array($data)) {
                throw new CommonException("任务 #{$index}: data 必填且必须是数组");
            }

            $bizKey = isset($options['biz_key']) ? (string)$options['biz_key'] : '';
            if ($bizKey === '') {
                throw new CommonException("任务 #{$index}: biz_key 必填");
            }

            $delaySec = (int)($options['delay_sec'] ?? 0);
            $priority = (int)($options['priority'] ?? 1);
            $queueGroup = (string)($options['queue_group'] ?? 'default');
            $maxRetries = (int)($options['max_retries'] ?? 5);  // 默认5，可自定义

            $payload = [
                'queue_type'  => $type,
                'biz_key'     => $bizKey,
                'params'      => $data,
                'priority'    => $priority,
                'ts'          => $now,
            ];

            $insertData[] = [
                'queue_type'   => $type,
                'biz_key'      => $bizKey,
                'queue_group'  => $queueGroup,
                'payload'      => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'status'       => SysTaskQueueEnum::STATUS_PENDING,
                'priority'     => $priority,
                'retry_count'  => 0,
                'max_retries'  => $maxRetries,  // 使用变量，支持自定义
                'error_message' => null,
                'scheduled_at' => $now + max(0, $delaySec),
                'version'      => 0,
                'create_at'    => $now,
                'update_at'    => $now,
            ];

            $payloads[] = [
                'payload' => $payload,
                'delay_sec' => $delaySec,
            ];
        }

        // 2. 批量插入数据库（外部调用已有事务，这里不需要事务）
        // 数据库已有唯一约束，biz_key 重复时会自动抛出异常
        $model->insertAll($insertData);

        // 3. 非 DB 驱动批量推送队列
        // 注意：Redis 推送失败不应该导致事务回滚，因为数据库是主数据源
        // 如果 Redis 推送失败，消费者可以从数据库消费（降级处理）
        if ($driverName !== 'db') {
            foreach ($payloads as $item) {
                $this->driver->push($item['payload'], $item['delay_sec']);
            }
        }
    }
}
