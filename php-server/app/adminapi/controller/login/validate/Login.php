<?php

namespace app\adminapi\controller\login\validate;

use app\deshang\base\BaseValidate;

class Login extends BaseValidate
{
    protected $rule = [
        'username' => 'require|max:50',
        'password' => 'require|min:6|max:20',
    ];

    protected $message = [
        'username.require' => '管理员名称不能为空',
        'username.max' => '管理员名称不能超过50个字符',
        'password.require' => '密码不能为空',
        'password.min' => '密码不能少于6个字符',
        'password.max' => '密码不能超过20个字符',
    ];

    protected $scene = [
        'login' => ['username', 'password'],
    ];
}
