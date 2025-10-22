<?php

namespace app\adminapi\controller\rider\validate;

use app\deshang\base\BaseValidate;

class RiderValidate extends BaseValidate
{
    protected $rule = [
        'user_id' => 'require|integer',
        'name' => 'max:32',
        'mobile' => 'mobile|max:20',
        'status' => 'in:0,1,2',
        'is_enabled' => 'in:0,1',
        'apply_status' => 'in:0,1,2',
        'audit_remark' => 'max:255',
    ];

    protected $message = [
        'user_id.require' => '用户ID不能为空',
        'user_id.integer' => '用户ID必须为整数',
        'name.max' => '骑手姓名不能超过32个字符',
        'mobile.mobile' => '手机号格式不正确',
        'mobile.max' => '手机号不能超过20个字符',
        'status.in' => '骑手状态只能为0、1、2',
        'is_enabled.in' => '启用状态只能为0或1',
        'apply_status.in' => '申请状态只能为0、1、2',
        'audit_remark.max' => '审核备注不能超过255个字符',
    ];

    protected $scene = [
        'create' => ['user_id'],
        'update' => ['name', 'mobile', 'status', 'is_enabled', 'apply_status', 'audit_remark'],
    ];
} 