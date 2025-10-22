<?php
namespace app\adminapi\controller\user\validate;

use app\deshang\base\BaseValidate;

// 用户ID的有效性  在修改方法进行验证
class UserGrowthValidate extends BaseValidate
{
    
    protected $rule = [
        'change_mode' => 'require|in:1,2', // 1:增加 2:减少
        'change_num' => 'require|float|gt:0|lt:100000',
        'user_id' => 'require|integer|gt:0'
    ];

    protected $message = [
        'change_mode.require' => '变更模式不能为空',
        'change_mode.in' => '变更模式值不正确',
        'change_num.require' => '变更数量不能为空',
        'change_num.float' => '变更数量必须为数字',
        'change_num.gt' => '变更数量必须大于0',
        'change_num.lt' => '变更数量不能超过100,000',
        'user_id.require' => '会员ID不能为空',
        'user_id.integer' => '会员ID必须为整数',
        'user_id.gt' => '会员ID必须大于0'
    ];

    protected $scene = [
        'change' => ['change_mode', 'change_num', 'user_id']
    ];

}