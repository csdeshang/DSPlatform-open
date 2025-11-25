<?php
namespace app\adminapi\controller\distributor\validate;

use app\deshang\base\BaseValidate;

// 用户ID的有效性  在修改方法进行验证
class DistributorBalanceValidate extends BaseValidate
{
    protected $rule = [
        'change_mode' => 'require|in:1,2', // 1:增加 2:减少
        'change_amount' => 'require|float|gt:0|elt:10000000',
        'distributor_user_id' => 'require|integer|gt:0'
    ];

    protected $message = [
        'change_mode.require' => '变更模式不能为空',
        'change_mode.in' => '变更模式值不正确',
        'change_amount.require' => '变更金额不能为空',
        'change_amount.float' => '变更金额必须为数字',
        'change_amount.gt' => '变更金额必须大于0',
        'change_amount.elt' => '变更金额不能超过10,000,000',
        'distributor_user_id.require' => '分销商ID不能为空',
        'distributor_user_id.integer' => '分销商ID必须为整数',
        'distributor_user_id.gt' => '分销商ID必须大于0'
    ];

    protected $scene = [
        'modify' => ['change_mode', 'change_amount', 'distributor_user_id']
    ];
}