<?php

namespace app\adminapi\controller\distributor\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\distributor\DistributorApplyEnum;

class DistributorApplyValidate extends BaseValidate
{
    protected $rule = [
        // 基础字段
        'id' => 'require|integer|min:1',
        'apply_status' => 'checkApplyStatus',
        'audit_remark' => 'max:500',
    ];

    protected $message = [
        // 基础字段
        'id.require' => '申请记录ID不能为空',
        'id.integer' => '申请记录ID必须为整数',
        'id.min' => '申请记录ID必须大于0',
        'apply_status.checkApplyStatus' => '申请状态值无效（0:待审核 1:已通过 2:已拒绝）',
        'audit_remark.max' => '审核备注不能超过500个字符',
    ];

    protected $scene = [
        // 分页查询
        'pages' => ['apply_status'],
        
        // 审核申请
        'audit' => ['id', 'apply_status', 'audit_remark'],
    ];


    public function checkApplyStatus($value, $rule, $data)
    {
        return array_key_exists($value, DistributorApplyEnum::getDistributorApplyStatusDict());
    }
} 