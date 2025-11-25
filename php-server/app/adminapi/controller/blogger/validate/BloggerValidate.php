<?php

namespace app\adminapi\controller\blogger\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\blogger\BloggerEnum;

class BloggerValidate extends BaseValidate
{
    protected $rule = [
        'blogger_name' => 'max:32',
        'description' => 'max:255',
        'verification_status' => 'checkVerificationStatus',
        'verification_type' => 'checkVerificationType',
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
        'verification_status.checkVerificationStatus' => '认证状态值无效（0:待认证 1:认证通过 2:认证拒绝）',
        'verification_type.checkVerificationType' => '认证类型值无效（1:个人认证 2:企业认证）',
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

    // 验证认证状态
    public function checkVerificationStatus($value, $rule, $data)
    {
        return array_key_exists($value, BloggerEnum::getVerificationStatusDict());
    }

    // 验证认证类型
    public function checkVerificationType($value, $rule, $data)
    {
        return array_key_exists($value, BloggerEnum::getVerificationTypeDict());
    }
} 