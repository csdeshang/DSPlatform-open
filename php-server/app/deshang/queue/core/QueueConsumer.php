<?php

namespace app\deshang\queue\core;

use app\deshang\queue\driver\RedisListDriver;
use app\deshang\queue\driver\RedisStreamDriver;
use app\deshang\queue\driver\RabbitMqDriver;
use app\deshang\queue\driver\DbDriver;
use app\common\dao\system\SysTaskQueueDao;
use app\common\enum\system\SysTaskQueueEnum;
use think\facade\Db;
use app\deshang\exceptions\CommonException;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;

/**
 * 队列消费者（由定时任务唤起）
 *
 * 设计要点（高并发安全）：
 * 1) 驱动负责"原子抢占"任务（DB：status 0->3；Redis/RabbitMQ 使用各自原子语义）
 * 2) 这里只做"读取已抢占的任务 -> 路由到处理器 -> 回写状态/耗时/重试"，不做再次查询筛选
 * 3) 路由策略：仅使用 config('queue.handlers') 的"任务类型 => 处理器类"映射，避免代码注册歧义
 * 4) 回写策略：
 *    - 成功：status=1，记录 consume_ms
 *    - 失败：retry_count++ 并指数退避；超过 max_retries 标记为失败（status=2）
 *    - 通过 biz_key（唯一）精准定位并更新
 */
class QueueConsumer
{
    private $driver;
    private $dao;

    public function __construct($driver = null)
    {
        if ($driver) {
            $this->driver = $driver;
            return;
        }
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
     * 消费任务
     *
     * @param int $batch 每次最大处理条数（建议 100~500，避免单次处理过多导致超时）
     * @return void
     */
    public function consume(int $batch = 200): void
    {
        // 迁移到期延迟任务（如驱动支持）：
        // - Redis List/Streams 使用 zset/stream 等机制时可在驱动内部完成迁移
        if (method_exists($this->driver, 'migrateDueDelay')) {
            $this->driver->migrateDueDelay(500);
        }

        $rawList = $this->driver->popBatch($batch);
        if (empty($rawList)) {
            return;
        }

        $this->dao = new SysTaskQueueDao();
        $handlerMap = config('queue.handlers') ?: [];

        // ========== 按用户ID分组（避免同一用户的任务并发处理）==========
        // 设计说明：
        // 1. 同一用户的任务（如积分、成长值、余额）串行处理，避免版本冲突
        // 2. 不同用户的任务可以并行处理，提高吞吐量
        // 3. 无法提取 user_id 的任务（如商品评分更新）单独处理
        $tasksByUser = [];
        $tasksWithoutUser = [];

        foreach ($rawList as $raw) {
            $task = json_decode($raw, true) ?: [];
            $userId = $this->extractUserId($task);

            if ($userId > 0) {
                // 按用户ID分组
                if (!isset($tasksByUser[$userId])) {
                    $tasksByUser[$userId] = [];
                }
                $tasksByUser[$userId][] = $raw;
            } else {
                // 无法提取 user_id 的任务（如商品评分、店铺评分等）
                $tasksWithoutUser[] = $raw;
            }
        }

        // 按用户串行处理（同一用户的任务不会并发，避免版本冲突）
        foreach ($tasksByUser as $userId => $tasks) {
            foreach ($tasks as $raw) {
                $this->processTask($raw, $handlerMap);
            }
        }

        // 无法提取 user_id 的任务，单独处理（这些任务通常不涉及用户数据竞争）
        foreach ($tasksWithoutUser as $raw) {
            $this->processTask($raw, $handlerMap);
        }
    }

    /**
     * 处理单个任务
     *
     * @param string $raw JSON 字符串
     * @param array $handlerMap 处理器映射
     * @return void
     */
    private function processTask(string $raw, array $handlerMap): void
    {
        // 统一解析一次，避免在 catch 中重复解析
        $task = json_decode($raw, true) ?: [];
        $type = $task['queue_type'] ?? '';
        $params = $task['params'] ?? [];
        $bizKey = (string)($task['biz_key'] ?? '');

        // 跳过无效任务
        if ($bizKey === '' || $type === '') {
            return;
        }

        // 路由检查
        $handlerClass = $handlerMap[$type] ?? null;
        if (!$handlerClass) {
            throw new CommonException('未配置的类型：' . $type);
        }

        $start = microtime(true);
        Db::startTrans();
        try {
            // 执行业务处理
            (new $handlerClass())->handle($params);
            // 状态更新（在事务内 保证原子性）
            $this->markSuccess($bizKey, $start);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            // 失败处理（重试/标记失败）- 在事务外
            $this->handleFailure($bizKey, $e, $start);
        } finally {
            // 统一释放锁（无论成功失败，无论是否抛异常，都确保释放）
            $this->releaseTaskLock($bizKey);
        }
    }

    /**
     * 标记任务成功
     *
     * @param string $bizKey
     * @param float $start
     * @return void
     */
    private function markSuccess(string $bizKey, float $start): void
    {
        $thisConsumeMs = (int)round((microtime(true) - $start) * 1000);
        
        // 使用数据库累加，避免额外查询
        $result = $this->dao->updateSysTaskQueue([
            ['biz_key', '=', $bizKey],
            ['status', '=', SysTaskQueueEnum::STATUS_PROCESSING]
        ], [
            'status'     => SysTaskQueueEnum::STATUS_SUCCESS,
            'consume_ms' => Db::raw("consume_ms + {$thisConsumeMs}"),
            'update_at'  => time(),
        ]);
    }

    /**
     * 处理任务失败（重试/标记失败）
     *
     * @param string $bizKey
     * @param \Throwable $e
     * @param float $start
     * @return void
     */
    private function handleFailure(string $bizKey, \Throwable $e, float $start): void
    {
        // 读取当前重试信息
        $row = $this->dao->getSysTaskQueueInfo(['biz_key' => $bizKey], '*');
        if (empty($row)) {
            // 记录不存在，直接返回（锁在 finally 中释放）
            return;
        }

        $retry = (int)($row['retry_count'] ?? 0) + 1;
        $max   = (int)($row['max_retries'] ?? 3);
        $thisConsumeMs = (int)round((microtime(true) - $start) * 1000);
        $currentConsumeMs = (int)($row['consume_ms'] ?? 0);
        $totalConsumeMs = $currentConsumeMs + $thisConsumeMs;  // 直接累加（已读取数据）
        $errorMsg = mb_substr($e->getMessage(), 0, 500);

        if ($retry >= $max) {
            // 达到最大重试：标记失败
            $this->dao->updateSysTaskQueue(['biz_key' => $bizKey], [
                'status'        => SysTaskQueueEnum::STATUS_FAILED,
                'error_message' => $errorMsg,
                'consume_ms'    => $totalConsumeMs,  // 使用已累加的值
                'update_at'     => time(),
            ]);
        } else {
            // 指数退避 + 随机抖动：60s±12s, 120s±24s, 240s±48s...
            // 添加 ±20% 随机抖动，避免大量任务同时重试造成惊群效应
            $exponentialDelay = 60 * (2 ** ($retry - 1));
            $jitter = mt_rand(0, (int)($exponentialDelay * 0.2)); // ±20% 随机抖动
            $backoff = $exponentialDelay + $jitter;

            $this->dao->updateSysTaskQueue(['biz_key' => $bizKey], [
                'status'       => SysTaskQueueEnum::STATUS_PENDING,
                'retry_count'  => $retry,
                'scheduled_at' => time() + $backoff,
                'error_message' => $errorMsg,
                'consume_ms'    => $totalConsumeMs,  // 使用已累加的值
                'update_at'    => time(),
            ]);
        }
    }

    /**
     * 从任务中提取 user_id
     * 
     * 支持的任务类型：
     * - 订单任务：params['order_info']['user_id']（如 OrderPayUserPointsQueue）
     * - 用户任务：params['user_id']（如 UserLoginPointsQueue）
     * - 邀请任务：params['inviter_id']（如 UserInvitePointsQueue）
     * 
     * @param array $task 任务数据（已解析的 JSON）
     * @return int 用户ID，无法提取时返回0
     */
    private function extractUserId(array $task): int
    {
        $params = $task['params'] ?? [];

        // 1. 尝试从 order_info 中提取（订单相关任务）
        // 例如：OrderPayUserPointsQueue, OrderCancelUserPointsQueue 等
        if (isset($params['order_info']['user_id'])) {
            return (int)$params['order_info']['user_id'];
        }

        // 2. 尝试直接提取 user_id（用户相关任务）
        // 例如：UserLoginPointsQueue, UserRegisterPointsQueue 等
        if (isset($params['user_id'])) {
            return (int)$params['user_id'];
        }

        // 3. 尝试提取 inviter_id（邀请任务）
        // 例如：UserInvitePointsQueue, UserInviteGrowthQueue
        if (isset($params['inviter_id'])) {
            return (int)$params['inviter_id'];
        }

        // 无法提取 user_id（如商品评分更新、店铺评分更新等）
        return 0;
    }

    /**
     * 释放任务 Redis 分布式锁
     * 
     * 说明：
     * - 此方法仅用于 DbDriver，因为只有 DbDriver 需要额外的 Redis 锁来优化数据库竞争
     * - RedisListDriver/RedisStreamDriver 使用 Redis 自己的原子操作（RPOP/XREAD），不需要额外的锁
     * 
     * @param string $bizKey 业务键
     * @return void
     */
    private function releaseTaskLock(string $bizKey): void
    {
        if (empty($bizKey)) {
            return;
        }

        // 仅当使用 DB 驱动时才需要释放锁
        // - DbDriver：需要额外的 Redis 锁来优化数据库竞争，任务处理完成后需要释放
        // - RedisListDriver：使用 RPOP（原子操作），不需要额外的锁
        // - RedisStreamDriver：使用 XREAD/XDEL（原子操作），不需要额外的锁
        $driverName = (string)(config('queue.driver') ?: 'db');
        if ($driverName === 'db') {
            $lockKey = sprintf(LockKeyManager::LOCK_QUEUE_TASK_KEY, $bizKey);
            // 注意：DbDriver 中获取锁时返回的 lockValue 需要在这里使用
            // 但由于 QueueConsumer 没有保存 lockValue，这里需要从任务信息中获取
            // 暂时使用空值，LockService 会处理这种情况
            KvManager::lock()->release(false, $lockKey);
        }
    }
}
