<?php

namespace app\adminapi\controller\technician\validate;

use app\deshang\base\BaseValidate;

/**
 * 师傅轨迹验证器
 */
class TechnicianTrackValidate extends BaseValidate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'technician_id' => 'require|integer|gt:0',
        'start_time' => 'date',
        'end_time' => 'date',
        'address' => 'max:255',
        'days' => 'integer|between:0,365',
    ];

    /**
     * 错误消息
     */
    protected $message = [
        'id.require' => '轨迹记录ID不能为空',
        'id.integer' => '轨迹记录ID必须为整数',
        'id.gt' => '轨迹记录ID必须大于0',
        'technician_id.require' => '师傅ID不能为空',
        'technician_id.integer' => '师傅ID必须为整数',
        'technician_id.gt' => '师傅ID必须大于0',
        'start_time.date' => '开始时间格式不正确',
        'end_time.date' => '结束时间格式不正确',
        'address.max' => '地址长度不能超过255个字符',
        'days.integer' => '保留天数必须为整数',
        'days.between' => '保留天数必须在0-365之间（0表示清空所有记录）',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'pages' => ['technician_id', 'start_time', 'end_time', 'address'],
        'info' => ['id'],
        'delete' => ['id'],
        'clear' => ['technician_id', 'days'],
    ];
} 