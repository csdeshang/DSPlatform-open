<?php

namespace app\common\model\system;

use app\deshang\base\BaseModel;
use app\common\enum\system\SysTaskQueueEnum;

class SysTaskQueueModel extends BaseModel
{
    /**

     * 模型名称
     * @var string
     */
    protected $name = 'sys_task_queue';



    // 状态描述 获取器
    public function getStatusDescAttr($value, $data)
    {
        return SysTaskQueueEnum::getStatusDesc($data['status']);
    }

    // 队列分组描述 获取器
    public function getQueueGroupDescAttr($value, $data)
    {
        return SysTaskQueueEnum::getQueueGroupDesc($data['queue_group']);
    }

    // 计划时间 获取器
    public function getScheduledAtAttr($value, $data)
    {
        return $this->formatTime($data['scheduled_at']);
    }

}
