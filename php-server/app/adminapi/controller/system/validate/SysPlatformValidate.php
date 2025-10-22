<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysPlatformValidate extends BaseValidate
{
    protected $rule = [
        'is_enable' => 'require|in:0,1',
    ];

    protected $message = [
        'is_enable.require' => '启用状态不能为空',
        'is_enable.in' => '启用状态只能为0或1',
    ];

    protected $scene = [
        'update' => ['is_enable'],
    ];
} 