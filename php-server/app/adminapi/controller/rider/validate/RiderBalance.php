<?php
namespace app\adminapi\controller\rider\validate;

use app\deshang\base\BaseValidate;

class RiderBalance extends BaseValidate
{
    protected $rule = [
        'change_mode' => 'require|in:1,2', // 1:充值 2:扣款
        'change_amount' => 'require|float|gt:0|lt:1000000',
        'rider_id' => 'require|integer|gt:0'
    ];

    protected $message = [
        'change_mode.require' => '变更模式不能为空',
        'change_mode.in' => '变更模式值不正确',
        'change_amount.require' => '变更金额不能为空',
        'change_amount.float' => '变更金额必须为数字',
        'change_amount.gt' => '变更金额必须大于0',
        'change_amount.lt' => '变更金额不能超过1,000,000',
        'rider_id.require' => '骑手ID不能为空',
        'rider_id.integer' => '骑手ID必须为整数',
        'rider_id.gt' => '骑手ID必须大于0'
    ];

    protected $scene = [
        'change' => ['change_mode', 'change_amount', 'rider_id']
    ];
}