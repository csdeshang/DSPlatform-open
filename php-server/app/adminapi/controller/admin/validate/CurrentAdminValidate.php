<?php

namespace app\adminapi\controller\admin\validate;

use app\deshang\base\BaseValidate;

class CurrentAdminValidate extends BaseValidate
{
    protected $rule = [
        'old_password' => 'require|min:1',
        'password' => 'require|min:6|max:32',
        'confirm_password' => 'require|confirm:password',
    ];

    protected $message = [
        'old_password.require' => '请输入原密码',
        'old_password.min' => '原密码不能为空',
        'password.require' => '请输入新密码',
        'password.min' => '新密码长度不能少于6位',
        'password.max' => '新密码长度不能超过32位',
        'confirm_password.require' => '请输入确认密码',
        'confirm_password.confirm' => '两次输入的密码不一致',
    ];

    protected $scene = [
        'editPassword' => ['old_password', 'password', 'confirm_password'],
    ];
} 