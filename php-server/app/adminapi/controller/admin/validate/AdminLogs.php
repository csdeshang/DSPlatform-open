<?php

namespace app\adminapi\controller\admin\validate;

use app\deshang\base\BaseValidate;

class AdminLogs extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer',
        'username' => 'max:50',
        'http_code' => 'integer',
        'controller' => 'max:64',
    ];

    protected $message = [
        'id.require' => '日志ID必填',
        'id.integer' => '日志ID必须是数字',
        'username.max' => '用户名长度不能超过50个字符',
        'http_code.integer' => 'HTTP状态码必须是数字',
        'controller.max' => '控制器名称长度不能超过64个字符',
    ];

    protected $scene = [
        'pages' => ['username', 'http_code', 'controller'],
        'info' => ['id'],
    ];
} 