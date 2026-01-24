<?php

namespace app\deshang\queue\driver;

use think\facade\Db;
use app\common\dao\system\SysTaskQueueDao;
use app\common\enum\system\SysTaskQueueEnum;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;

/**
 * 数据库队列驱动（sys_task_queue）
 *
 * 设计要点：
 * - push：统一通过 Dao 新增一条待处理记录（status=0）。
 * - migrateDueDelay：DB 驱动通过 scheduled_at 条件控制到期，不需要迁移实现。
 * - popBatch：
 *   1) 预取候选（status=0 且 scheduled_at 到期，按 retry_count/priority/scheduled_at/id 排序）
 *   2) 排序策略：重试任务优先（retry_count DESC），然后按优先级、计划时间、ID排序
 *   3) 逐条"原子抢占" status:0->3（where id=? and status=0），受影响行数>0 才算抢到
 *   4) 返回 JSON 字符串数组（与 Producer 的 payload 保持一致），便于不同驱动统一消费
 *
 * 并发安全：
 * - 通过单条 UPDATE 的受影响行数判定是否抢占成功，避免多进程重复消费同一条。
 */
class DbDriver
{

    public function push(array $payload, int $delaySec = 0): void
    {
        $now = time();
        (new SysTaskQueueDao())->createSysTaskQueue([
            'queue_type'   => $payload['queue_type'] ?? '',
            'queue_group'  => $payload['queue_group'] ?? SysTaskQueueEnum::GROUP_DEFAULT,
            'biz_key'      => $payload['biz_key'] ?? '',
            'payload'      => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'status'       => SysTaskQueueEnum::STATUS_PENDING,
            'priority'     => $payload['priority'] ?? SysTaskQueueEnum::PRIORITY_NORMAL,
            'retry_count'  => 0,
            'max_retries'  => 3,
            'error_message'=> null,
            'scheduled_at' => $now + max(0, (int)$delaySec),
            'version'      => 0,
            'create_at'    => $now,
            'update_at'    => $now,
        ]);
    }

    public function migrateDueDelay(int $max = 500): void
    {
        // DB 驱动无需迁移：通过 scheduled_at<=now 过滤到期消息
    }

    /**
     * 原子锁定并返回待处理任务的 JSON body 数组
     * 
     * 性能优化（双层保护 + 批量 UPDATE）：
     * 1. 数据库索引优化：
     *    - 使用 idx_pop_batch 复合索引（status, scheduled_at, retry_count, priority, id）
     *    - 查询条件顺序与索引字段顺序匹配，确保索引被正确使用
     *    - 排序字段与索引字段匹配，避免 filesort
     * 
     * 2. Redis 分布式锁优化：
     *    - 基于 biz_key 的 Redis 锁，在数据库 UPDATE 前快速过滤
     *    - 减少多个消费者同时获取相同任务导致的数据库竞争
     *    - 锁超时时间 300 秒（5 分钟），确保任务处理完成后自动释放
     * 
     * 3. 批量 UPDATE 优化：
     *    - 先通过 Redis 锁过滤，获取锁的任务暂存
     *    - 批量 UPDATE 所有获取锁的任务（1次 UPDATE vs N次 UPDATE）
     *    - 查询确认哪些真正更新成功，释放未成功任务的锁
     * 
     * @param int $max 最大获取数量
     * @return array<string> 任务 payload 数组
     */
    public function popBatch(int $max = 200): array
    {
        $now = time();
        $dao = new SysTaskQueueDao();
        
        // ========== 第一步：查询候选任务（使用优化后的索引）==========
        // ========== 优化排序：重试任务优先处理 ==========
        // 排序逻辑：
        // 1. 重试任务优先（retry_count DESC）- 失败多次的任务更需要尽快处理
        // 2. 优先级高的优先（priority DESC）
        // 3. 计划执行时间早的优先（scheduled_at ASC）
        // 4. ID小的优先（id ASC，保证顺序一致性）
        // 
        // 索引使用：idx_pop_batch (status, scheduled_at, retry_count, priority, id)
        // - WHERE 条件：status = 0 AND scheduled_at <= now（使用索引前两个字段）
        // - ORDER BY：retry_count DESC, priority DESC, scheduled_at ASC, id ASC（索引覆盖排序）
        $candidates = $dao->getSysTaskQueueList(
            [
                ['status', '=', SysTaskQueueEnum::STATUS_PENDING],      // 索引第1个字段
                ['scheduled_at', '<=', $now],                            // 索引第2个字段
            ],
            'id,payload,biz_key,priority,scheduled_at,create_at,status,queue_group,retry_count',
            'retry_count DESC, priority DESC, scheduled_at ASC, id ASC', // 索引覆盖排序
            $max * 2  // 预取候选（放大 2 倍，尽量抢到 batch 数量）
        );
        
        if (empty($candidates)) {
            return [];
        }

        // ========== 第二步：Redis 锁快速过滤 ==========
        $lockedTasks = [];
        foreach ($candidates as $task) {
            if (count($lockedTasks) >= $max) {
                break;
            }
            
            // 优先使用查询字段中的 biz_key（避免解析 JSON）
            $bizKey = (string)($task['biz_key'] ?? '');
            if ($bizKey === '') {
                // 如果查询字段中没有 biz_key，尝试从 payload 解析（兼容旧数据）
                $taskData = json_decode($task['payload'], true) ?: [];
                $bizKey = (string)($taskData['biz_key'] ?? '');
                if ($bizKey === '') {
                    continue; // 跳过没有 biz_key 的任务
                }
            }
            
            $lockKey = sprintf(LockKeyManager::LOCK_QUEUE_TASK_KEY, $bizKey);
            $lockValue = KvManager::lock()->acquire($lockKey, 300);
            if (!$lockValue) {
                // 获取锁失败，已被其他消费者处理，跳过
                continue;
            }
            
            // 获取锁成功，暂存任务信息（包含 lockValue 用于后续释放）
            $lockedTasks[] = [
                'id' => (int)$task['id'],
                'payload' => $task['payload'],
                'lock_key' => $lockKey,
                'lock_value' => $lockValue,
            ];
        }
        
        if (empty($lockedTasks)) {
            return [];
        }

        // ========== 第三步：批量 UPDATE ==========
        $ids = array_column($lockedTasks, 'id');
        $dao->updateSysTaskQueue(
            [
                ['id', 'in', $ids],
                ['status', '=', SysTaskQueueEnum::STATUS_PENDING]
            ],
            [
                'status' => SysTaskQueueEnum::STATUS_PROCESSING,
                'update_at' => time()
            ]
        );

        // ========== 第四步：查询确认（哪些真正更新成功）==========
        $updated = $dao->getSysTaskQueueList(
            [
                ['id', 'in', $ids],
                ['status', '=', SysTaskQueueEnum::STATUS_PROCESSING]
            ],
            'id,payload',
            'id ASC',
            $max
        );

        // ========== 第五步：释放未成功任务的锁 ==========
        // 将数组转换为哈希表，O(1) 查找性能（替代 in_array 的 O(n) 查找）
        $updatedIdsMap = array_flip(array_column($updated, 'id'));
        $result = [];
        
        foreach ($lockedTasks as $task) {
            if (isset($updatedIdsMap[$task['id']])) {
                // 更新成功，返回 payload（锁在任务处理完成后由 QueueConsumer 释放）
                $result[] = $task['payload'];
            } else {
                // 更新失败（可能已被其他消费者处理），立即释放锁
                KvManager::lock()->release($task['lock_value'] ?? false, $task['lock_key']);
            }
        }
        
        return $result;
    }
}
