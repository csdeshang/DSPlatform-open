<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\system\SysTaskQueueEnum;

class SysTaskQueueValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // ID，必须是整数，且大于0
        'queue_type' => 'max:50', // 任务类型，最大长度50（对应数据库 varchar(50)）
        'queue_group' => 'max:50', // 队列分组，最大长度50（对应数据库 varchar(50)）
        'biz_key' => 'max:100', // 业务幂等键，最大长度100（对应数据库 varchar(100)）
        'status' => 'checkStatus', // 任务状态，使用枚举验证
        'priority' => 'integer|egt:0', // 优先级，必须是整数，且大于等于0
        'retry_count' => 'integer|egt:0', // 重试次数，必须是整数，且大于等于0
        'consume_ms_min' => 'integer|egt:0', // 最小耗时，必须是整数，且大于等于0
        'consume_ms_max' => 'integer|egt:0', // 最大耗时，必须是整数，且大于等于0
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须是整数',
        'id.gt' => 'ID必须大于0',
        'queue_type.max' => '任务类型不能超过50个字符',
        'queue_group.max' => '队列分组不能超过50个字符',
        'biz_key.max' => '业务幂等键不能超过100个字符',
        'status.checkStatus' => '任务状态值无效（0:待处理 1:完成 2:失败 3:处理中）',
        'priority.integer' => '优先级必须是整数',
        'priority.egt' => '优先级必须大于等于0',
        'retry_count.integer' => '重试次数必须是整数',
        'retry_count.egt' => '重试次数必须大于等于0',
        'consume_ms_min.integer' => '最小耗时必须是整数',
        'consume_ms_min.egt' => '最小耗时必须大于等于0',
        'consume_ms_max.integer' => '最大耗时必须是整数',
        'consume_ms_max.egt' => '最大耗时必须大于等于0',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['queue_type', 'queue_group', 'biz_key', 'status', 'priority', 'retry_count', 'consume_ms_min', 'consume_ms_max'], // 分页查询场景
        'info' => ['id'], // 获取详情场景
    ];

    // 验证任务状态
    public function checkStatus($value, $rule, $data)
    {
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, SysTaskQueueEnum::getStatusDict());
    }
}

