<?php

namespace app\adminapi\controller\rider\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\rider\RiderEnum;

class RiderValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'user_id' => 'require|integer',
        'username' => 'max:32',
        'name' => 'max:32',
        'mobile' => 'mobile|max:20',
        'status' => 'checkRiderStatus',
        'is_enabled' => 'in:0,1',
        'apply_status' => 'checkApplyStatus',
        'audit_remark' => 'max:255',
    ];

    protected $message = [
        'id.require' => '骑手ID不能为空',
        'id.integer' => '骑手ID必须是整数',
        'id.gt' => '骑手ID必须大于0',
        'user_id.require' => '用户ID不能为空',
        'user_id.integer' => '用户ID必须为整数',
        'username.max' => '用户名长度不能超过32个字符',
        'name.max' => '骑手姓名不能超过32个字符',
        'mobile.mobile' => '手机号格式不正确',
        'mobile.max' => '手机号不能超过20个字符',
        'status.checkRiderStatus' => '骑手状态值无效（0:休息 1:接单 2:忙碌）',
        'is_enabled.in' => '启用状态只能为0或1',
        'apply_status.checkApplyStatus' => '申请状态值无效（0:审核中 1:审核通过 2:拒绝）',
        'audit_remark.max' => '审核备注不能超过255个字符',
    ];

    protected $scene = [
        'pages' => ['username', 'name', 'mobile', 'apply_status'],
        'create' => ['user_id'],
        'update' => ['name', 'mobile', 'status', 'is_enabled', 'apply_status', 'audit_remark'],
        'audit' => ['id', 'apply_status', 'audit_remark'],
        'softDelete' => ['id'],
        'restore' => ['id'],
    ];

    // 验证骑手状态
    public function checkRiderStatus($value, $rule, $data)
    {
        // 空值允许（用于更新时可选字段），但 '0' 是有效值需要验证
        if ($value === '' || $value === null) {
            return true;
        }
        return array_key_exists($value, RiderEnum::getRiderStatusDict());
    }

    // 验证申请状态
    public function checkApplyStatus($value, $rule, $data)
    {
        // 空值允许（用于分页查询时"全部"选项），但 '0' 是有效值需要验证
        if ($value === '' || $value === null) {
            return true;
        }
        return array_key_exists($value, RiderEnum::getApplyStatusDict());
    }
} 