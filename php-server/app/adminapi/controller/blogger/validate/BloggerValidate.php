<?php

namespace app\adminapi\controller\blogger\validate;

use app\deshang\base\BaseValidate;

class BloggerValidate extends BaseValidate
{
    protected $rule = [
        'blogger_name' => 'max:32',
        'description' => 'max:255',
        'verification_status' => 'in:0,1,2',
        'verification_type' => 'in:0,1,2',
        'verification_desc' => 'max:255',
        'is_live_enabled' => 'in:0,1',
        'is_drama_enabled' => 'in:0,1',
        'is_enabled' => 'in:0,1',
        'id' => 'require|integer',
        'field' => 'require|in:is_live_enabled,is_drama_enabled,is_enabled',
    ];

    protected $message = [
        'blogger_name.max' => '博主昵称不能超过32个字符',
        'description.max' => '描述不能超过255个字符',
        'verification_status.in' => '认证状态只能为0、1、2',
        'verification_type.in' => '认证类型只能为0、1、2',
        'verification_desc.max' => '认证说明不能超过255个字符',
        'is_live_enabled.in' => '直播权限只能为0或1',
        'is_drama_enabled.in' => '短剧权限只能为0或1',
        'is_enabled.in' => '可用状态只能为0或1',
        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须为整数',
        'field.require' => '字段名不能为空',
        'field.in' => '字段名不正确',
    ];

    protected $scene = [
        'update' => ['blogger_name', 'description', 'verification_status', 'verification_type', 'verification_desc', 'is_live_enabled', 'is_drama_enabled', 'is_enabled'],
        'toggle' => ['id', 'field'],
    ];
} 