<?php
namespace app\adminapi\controller\user\validate;

use app\deshang\base\BaseValidate;

// 用户ID的有效性  在修改方法进行验证
class UserBalance extends BaseValidate
{
    protected $rule = [
        'change_mode' => 'require|in:1,2', // 1:充值 2:扣款
        'change_amount' => 'require|float|gt:0|lt:1000000',
        'user_id' => 'require|integer|gt:0'
    ];

    protected $message = [
        'change_mode.require' => '变更模式不能为空',
        'change_mode.in' => '变更模式值不正确',
        'change_amount.require' => '变更金额不能为空',
        'change_amount.float' => '变更金额必须为数字',
        'change_amount.gt' => '变更金额必须大于0',
        'change_amount.lt' => '变更金额不能超过1,000,000',
        'user_id.require' => '会员ID不能为空',
        'user_id.integer' => '会员ID必须为整数',
        'user_id.gt' => '会员ID必须大于0'
    ];

    protected $scene = [
        'modify' => ['change_mode', 'change_amount', 'user_id']
    ];
}