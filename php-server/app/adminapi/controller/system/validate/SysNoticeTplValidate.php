<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysNoticeTplValidate extends BaseValidate
{
    protected $rule = [
        'interna_template' => 'max:500',
        'email_switch' => 'in:0,1',
        'email_template' => 'max:1000',
        'sms_switch' => 'in:0,1',
        'sms_template_id' => 'max:50',
        'sms_template' => 'max:500',
        'wechat_official_switch' => 'in:0,1',
        'wechat_official_template_id' => 'max:50',
        'wechat_mini_switch' => 'in:0,1',
        'wechat_mini_template_id' => 'max:50',
        // 测试相关验证规则
        'user_id' => 'require|number',
        'template_key' => 'require|max:50',
        'template_params' => 'array',
        // 切换字段相关验证规则
        'id' => 'require|number',
        'field' => 'require|in:email_switch,sms_switch,wechat_official_switch,wechat_mini_switch',
    ];

    protected $message = [
        'interna_template.max' => '站内信模板不能超过500个字符',
        'email_switch.in' => '邮件开关只能为0或1',
        'email_template.max' => '邮件模板不能超过1000个字符',
        'sms_switch.in' => '短信开关只能为0或1',
        'sms_template_id.max' => '短信模板ID不能超过50个字符',
        'sms_template.max' => '短信模板不能超过500个字符',
        'wechat_official_switch.in' => '微信公众号开关只能为0或1',
        'wechat_official_template_id.max' => '微信公众号模板ID不能超过50个字符',
        'wechat_mini_switch.in' => '微信小程序开关只能为0或1',
        'wechat_mini_template_id.max' => '微信小程序模板ID不能超过50个字符',
        // 测试相关验证消息
        'user_id.require' => '用户ID不能为空',
        'user_id.number' => '用户ID必须为数字',
        'template_key.require' => '模板标识不能为空',
        'template_key.max' => '模板标识不能超过50个字符',
        'template_params.array' => '模板参数必须为数组格式',
        // 切换字段相关验证消息
        'id.require' => '通知模板ID不能为空',
        'id.number' => '通知模板ID必须为数字',
        'field.require' => '字段名不能为空',
        'field.in' => '字段名只能是email_switch,sms_switch,wechat_official_switch,wechat_mini_switch',
    ];

    protected $scene = [
        'update' => ['interna_template', 'email_switch', 'email_template', 'sms_switch', 'sms_template_id', 'sms_template', 'wechat_official_switch', 'wechat_official_template_id', 'wechat_mini_switch', 'wechat_mini_template_id'],
        'test' => ['user_id', 'template_key', 'template_params'],
        'toggle' => ['id', 'field'],
    ];
} 