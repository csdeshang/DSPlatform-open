<?php

namespace app\common\enum\system;

/**
 * 系统任务队列枚举定义  对应表：sys_task_queue
 *
 * 参考字段（deshang.sql 1103-1128）：
 * - status: 0=待处理,1=完成,2=失败,3=处理中
 * - priority: 越高越先处理（tinyint）
 * - queue_type: 任务类型（如：OrderGenerateSalesIncQueue / OrderCancelSalesDecQueue）
 * - queue_group: 队列分组（默认 default）
 */
class SysTaskQueueEnum
{
    // 任务状态
    const STATUS_PENDING = 0;      // 待处理
    const STATUS_SUCCESS = 1;      // 完成
    const STATUS_FAILED = 2;       // 失败
    const STATUS_PROCESSING = 3;   // 处理中

    // 获取任务状态字典
    public static function getStatusDict(): array
    {
        return [
            self::STATUS_PENDING => '待处理',
            self::STATUS_SUCCESS => '完成',
            self::STATUS_FAILED => '失败',
            self::STATUS_PROCESSING => '处理中',
        ];
    }

    // 获取任务状态描述
    public static function getStatusDesc($value): string
    {
        $data = self::getStatusDict();
        return $data[$value] ?? '未知状态';
    }

    // 优先级（数值越大优先级越高）
    const PRIORITY_NORMAL = 0;   // 普通
    const PRIORITY_HIGH = 1;     // 较高
    const PRIORITY_URGENT = 2;   // 最高

    // 获取优先级字典
    public static function getPriorityDict(): array
    {
        return [
            self::PRIORITY_NORMAL => '普通',
            self::PRIORITY_HIGH => '较高',
            self::PRIORITY_URGENT => '最高',
        ];
    }

    // 获取优先级描述
    public static function getPriorityDesc($value): string
    {
        $data = self::getPriorityDict();
        return $data[$value] ?? '未知优先级';
    }

    // 队列分组
    const GROUP_DEFAULT = 'default';
    const GROUP_ORDER = 'order';
    const GROUP_USER = 'user';  

    // 获取分组字典
    public static function getQueueGroupDict(): array
    {
        return [
            self::GROUP_DEFAULT => '默认分组',
            self::GROUP_ORDER => '订单分组',
            self::GROUP_USER => '用户分组',
        ];
    }

    // 获取分组描述
    public static function getQueueGroupDesc($value): string
    {
        $data = self::getQueueGroupDict();
        return $data[$value] ?? '未知分组';
    }


}


