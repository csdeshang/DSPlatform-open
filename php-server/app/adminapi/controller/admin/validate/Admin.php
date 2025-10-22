<?php
namespace app\adminapi\controller\admin\validate;

use app\deshang\base\BaseValidate;

class Admin extends BaseValidate
{

    protected $rule = [
        'id' => 'require|integer',
        'username' => 'require|min:3|max:20|unique:admin',
        'password' => 'require|min:6',
        'confirm_password' => 'min:6|confirm:password',
        'role_id' => 'require|integer',
        'http_code' => 'integer',
    ];

    protected $message = [
        'id.require' => '管理员ID必填',
        'id.integer' => '管理员ID必须是数字',
        'username.require' => '管理员用户名必填',
        'username.min' => '管理员用户名长度不能小于3',
        'username.max' => '管理员用户名长度不能超过20',
        'username.unique' => '管理员用户名已存在',
        'password.require' => '管理员密码必填',
        'password.min' => '管理员密码长度至少为6位',
        'confirm_password.min' => '管理员密码长度至少为6位',
        'confirm_password.confirm' => '两次输入的密码不一致',
        'role_id.require' => '请选择权限组',
        'role_id.integer' => '权限组ID必须是数字',
        'http_code.integer' => 'HTTP状态码必须是数字',
    ];

    protected $scene = [
        'create' => ['username', 'password', 'confirm_password', 'role_id'],
        'update' => ['id', 'password', 'confirm_password', 'role_id'],
        'pages' => ['http_code'],
    ];

}