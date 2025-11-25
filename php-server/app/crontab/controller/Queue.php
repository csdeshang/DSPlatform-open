<?php

namespace app\crontab\controller;

use app\deshang\base\controller\BaseApiController;
use app\deshang\exceptions\CommonException;
use app\deshang\queue\core\QueueConsumer;

class Queue extends BaseApiController
{
    /**
     * 入口：默认执行一次消费
     */
    public function index()
    {
        return $this->consume();
    }

    /**
     * 消费任务
     * - 支持 query 参数 batch 指定每次最大处理条数（默认 100）
     * - 由计划任务/定时器调用：/crontab/queue/consume?batch=100
     */
    public function consume()
    {
        $batch = (int) input('batch/d', 100);
        if ($batch <= 0) $batch = 100;

        try {
            (new QueueConsumer())->consume($batch);
            return ds_json_success('执行成功');
        } catch (\Throwable $e) {
            throw new CommonException('队列消费失败：' . $e->getMessage());
        }
    }
}
