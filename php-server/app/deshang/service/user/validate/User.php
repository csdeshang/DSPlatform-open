<?php

namespace app\deshang\service\user\validate;
use think\Validate;


class User extends Validate {

    protected $rule = [
        'username' => 'require|alphaDash|length:3,20|unique:user',
        'password' => 'require|length:6,20',
        'idcard_name' => 'length:1,20',
        'nickname' => 'max:20',
        'idcard_number' => 'idCard',
        'email' => 'email|unique:user',
        'mobile' => 'mobile|unique:user',
        'qq' => 'length:5,12',
        'user_wxunionid' => 'unique:user',
    ];
    protected $message = [
        'username.require' => '用户名必填',
        'username.alphaDash' => '用户名只能为字母、数字、下划线、破折号',
        'username.length' => '用户名长度在3到20位',
        'username.unique' => '用户名已存在',
        'password.require' => '密码为必填',
        'password.length' => '密码长度必须为6-20之间',
        'idcard_name.length' => '真实姓名不能超过20字符',
        'nickname.max' => '会员昵称长度不能超过20位',
        'idcard_number.idCard' => '身份证错误',
        'email.email' => '邮箱格式错误',
        'mobile.mobile' => '手机格式错误',
        'mobile.unique' => '手机号已被绑定',
        'qq.length' => 'QQ的长度应该在5至12位之间',
        'user_wxunionid.unique' => '微信号已被绑定',
    ];
    protected $scene = [
        'add' => ['username', 'password', 'idcard_name', 'nickname', 'idcard_number', 'email', 'mobile', 'qq', 'user_wxunionid'],
        'edit' => ['idcard_name', 'nickname', 'idcard_number', 'email', 'mobile', 'qq',  'user_wxunionid'],
    ];
    
}