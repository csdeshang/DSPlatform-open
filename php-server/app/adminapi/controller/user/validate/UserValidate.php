<?php

namespace app\adminapi\controller\user\validate;

use app\deshang\base\BaseValidate;

class UserValidate extends BaseValidate
{
    protected $rule = [
        // 基础字段
        'id' => 'require|integer|min:1',
        'username' => 'require|max:50|alphaNum',
        'password' => 'require|min:6|max:20',
        'confirm_password' => 'require|confirm:password',
        'pay_password' => 'min:6|max:20',
        
        // 个人信息
        'nickname' => 'max:30',
        'sex' => 'in:0,1,2',
        'birthday' => 'integer',
        'email' => 'email|max:100',
        'email_bind' => 'in:0,1',
        'mobile' => 'mobile|max:20',
        'mobile_bind' => 'in:0,1',
        'qq' => 'max:20',
        'idcard_name' => 'max:50',
        
        // 状态字段
        'is_enabled' => 'in:0,1',
        'is_distributor' => 'in:0,1',
        
        // 推广关系
        'inviter_id' => 'require|integer|min:1',
    ];

    protected $message = [
        // 基础字段
        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须为整数',
        'id.min' => 'ID必须大于0',
        'username.require' => '用户名不能为空',
        'username.max' => '用户名不能超过50个字符',
        'username.alphaNum' => '用户名只能包含字母和数字',
        'password.require' => '密码不能为空',
        'password.min' => '密码长度不能少于6个字符',
        'password.max' => '密码长度不能超过20个字符',
        'confirm_password.require' => '确认密码不能为空',
        'confirm_password.confirm' => '确认密码与密码不一致',
        'pay_password.min' => '支付密码长度不能少于6个字符',
        'pay_password.max' => '支付密码长度不能超过20个字符',
        
        // 个人信息
        'nickname.max' => '昵称不能超过30个字符',
        'sex.in' => '性别值无效',
        'birthday.integer' => '生日时间戳格式错误',
        'email.email' => '邮箱格式不正确',
        'email.max' => '邮箱长度不能超过100个字符',
        'email_bind.in' => '邮箱绑定状态值无效',
        'mobile.mobile' => '手机号码格式不正确',
        'mobile.max' => '手机号码长度不能超过20个字符',
        'mobile_bind.in' => '手机绑定状态值无效',
        'qq.max' => 'QQ号长度不能超过20个字符',
        'idcard_name.max' => '身份证姓名长度不能超过50个字符',
        
        // 状态字段
        'is_enabled.in' => '启用状态值无效',
        'is_distributor.in' => '分销商状态值无效',
        
        // 推广关系
        'inviter_id.require' => '推广人ID不能为空',
        'inviter_id.integer' => '推广人ID必须为整数',
        'inviter_id.min' => '推广人ID必须大于0',
    ];

    protected $scene = [
        // 分页查询
        'pages' => ['is_distributor'],
        
        // 创建用户
        'create' => ['username', 'password', 'confirm_password'],
        
        // 更新用户
        'update' => ['nickname', 'sex', 'birthday', 'email', 'email_bind', 'mobile', 'mobile_bind', 'qq', 'idcard_name', 'is_enabled'],
        
        // 获取用户详情
        'info' => ['id'],
        
        // 获取推广关系
        'relation' => ['inviter_id'],
    ];
}
