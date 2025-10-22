<?php

namespace app\adminapi\controller\wechat\validate;

use think\Validate;

class WechatOfficialTemplateValidate extends Validate
{
    protected $rule = [
        'keys' => 'require|array',
        'key' => 'require|max:100',
    ];

    protected $message = [
        'keys.require' => '模板keys不能为空',
        'keys.array' => '模板keys必须是数组',
        'key.require' => '模板key不能为空',
        'key.max' => '模板key长度不能超过100个字符',
    ];

    protected $scene = [
        'sync' => ['keys'],
        'delete' => ['key'],
    ];
} 