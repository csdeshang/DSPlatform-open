<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\system\SysTaskQueueDao;
use app\common\enum\system\SysTaskQueueEnum;

class SysTaskQueueService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    // 分页列表
    public function getSysTaskQueuePages(array $data)
    {
        $condition = [];
        if (!empty($data['queue_type'])) {
            $condition[] = ['queue_type', 'like', '%' . $data['queue_type'] . '%'];
        }
        if (!empty($data['queue_group'])) {
            $condition[] = ['queue_group', 'like', '%' . $data['queue_group'] . '%'];
        }
        if (!empty($data['biz_key'])) {
            $condition[] = ['biz_key', 'like', '%' . $data['biz_key'] . '%'];
        }
        if ($data['status'] !== null && $data['status'] !== '') {
            $condition[] = ['status', '=', (int)$data['status']];
        }
        if ($data['priority'] !== null && $data['priority'] !== '') {
            $condition[] = ['priority', '>=', (int)$data['priority']];
        }
        if ($data['retry_count'] !== null && $data['retry_count'] !== '') {
            $condition[] = ['retry_count', '>=', (int)$data['retry_count']];
        }
        if ($data['consume_ms_min'] !== null && $data['consume_ms_min'] !== '') {
            $condition[] = ['consume_ms', '>=', (int)$data['consume_ms_min']];
        }
        if ($data['consume_ms_max'] !== null && $data['consume_ms_max'] !== '') {
            $condition[] = ['consume_ms', '<=', (int)$data['consume_ms_max']];
        }
        return (new SysTaskQueueDao())->getSysTaskQueuePages($condition, '*', 'id desc');
    }

    // 详情
    public function getSysTaskQueueInfo(int $id)
    {
        return (new SysTaskQueueDao())->getSysTaskQueueInfoById($id, '*');
    }

    /**
     * 恢复孤儿任务（长时间处于 PROCESSING 状态的任务）
     * 
     * 查找超过30分钟未更新的 PROCESSING 状态任务，将其状态重置为 PENDING，让任务重新处理
     * max_retries 在原有基础上增加 5 次，retry_count 保持不变
     * 
     * @return array 包含 count（恢复的任务数量）
     */
    public function recoverOrphanedTasks(): array
    {
        $timeoutMinutes = 30; // 默认30分钟
        $timeout = time() - ($timeoutMinutes * 60);
        
        // 查找长时间处于 PROCESSING 状态的任务（需要获取 max_retries 字段）
        $stuckTasks = (new SysTaskQueueDao())->getSysTaskQueueList([
            ['status', '=', SysTaskQueueEnum::STATUS_PROCESSING],
            ['update_at', '<', $timeout], // 超过30分钟未更新
        ], 'id,biz_key,max_retries', 'update_at ASC', 500);
        
        if (empty($stuckTasks)) {
            return ['count' => 0];
        }
        
        $recovered = 0;
        foreach ($stuckTasks as $task) {
            // 在原有 max_retries 基础上增加 5 次（tinyint 最大值是 255）
            $newMaxRetries = min(255, (int)($task['max_retries'] ?? 3) + 5);
            
            // 将状态改回 PENDING，增加 max_retries，不修改 retry_count
            // 添加随机延迟（0-60秒），避免大量任务同时执行造成系统压力
            $randomDelay = mt_rand(0, 60); // 随机延迟0-60秒
            $affected = (new SysTaskQueueDao())->updateSysTaskQueue([
                ['id', '=', $task['id']],
                ['status', '=', SysTaskQueueEnum::STATUS_PROCESSING] // 条件：必须是 PROCESSING 状态
            ], [
                'status' => SysTaskQueueEnum::STATUS_PENDING,
                'max_retries' => $newMaxRetries, // 增加最大重试次数
                'scheduled_at' => time() + $randomDelay, // 随机延迟执行，分散系统压力
                'update_at' => time(),
            ]);
            
            if ($affected > 0) {
                $recovered++;
            }
        }
        
        return ['count' => $recovered];
    }

    /**
     * 批量恢复失败任务
     * 
     * 查找所有失败状态的任务，将其状态重置为 PENDING，让任务重新处理
     * max_retries 在原有基础上增加 5 次，retry_count 保持不变
     * 
     * @return array 包含 count（恢复的任务数量）
     */
    public function retryFailedTasks(): array
    {
        $dao = new SysTaskQueueDao();
        
        // 查找所有失败状态的任务（需要获取 max_retries 字段）
        $failedTasks = $dao->getSysTaskQueueList([
            ['status', '=', SysTaskQueueEnum::STATUS_FAILED],
        ], 'id,max_retries', 'id ASC', 1000);
        
        if (empty($failedTasks)) {
            return ['count' => 0];
        }
        
        $recovered = 0;
        foreach ($failedTasks as $task) {
            // 在原有 max_retries 基础上增加 5 次（tinyint 最大值是 255）
            $newMaxRetries = min(255, (int)($task['max_retries'] ?? 3) + 5);
            
            // 将状态改回 PENDING，增加 max_retries，不修改 retry_count
            // 添加随机延迟（0-60秒），避免大量任务同时执行造成系统压力
            $randomDelay = mt_rand(0, 60); // 随机延迟0-60秒
            $affected = $dao->updateSysTaskQueue([
                ['id', '=', $task['id']],
                ['status', '=', SysTaskQueueEnum::STATUS_FAILED] // 条件：必须是失败状态
            ], [
                'status' => SysTaskQueueEnum::STATUS_PENDING,
                'max_retries' => $newMaxRetries, // 增加最大重试次数
                'error_message' => null, // 清空错误信息
                'scheduled_at' => time() + $randomDelay, // 随机延迟执行，分散系统压力
                'update_at' => time(),
            ]);
            
            if ($affected > 0) {
                $recovered++;
            }
        }
        
        return ['count' => $recovered];
    }
}
