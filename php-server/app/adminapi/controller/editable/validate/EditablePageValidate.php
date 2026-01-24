<?php

namespace app\adminapi\controller\editable\validate;

use app\deshang\base\BaseValidate;


class EditablePageValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'title' => 'require|max:255', // 标题必填，最大长度255
        'platform' => 'require|max:10|checkPlatform', // 平台必填，最大长度255，使用自定义验证方法
        'device_type' => 'in:pc,mobile', // 设备类型只能是pc或mobile
    ];

    // 定义错误信息
    protected $message = [
        'title.require' => '标题不能为空',
        'title.max' => '标题不能超过255个字符',
        'platform.require' => '平台不能为空',
        'platform.max' => '平台不能超过10个字符',
        'platform.checkPlatform' => '平台名称无效,请确认是否安装', // 自定义验证错误提示
        'device_type.in' => '设备类型只能是pc或mobile',
    ];

    // 定义场景
    protected $scene = [
        'create' => ['title', 'platform', 'device_type'], // 创建场景
        'update' => ['title'], // 更新场景
    ];


    
}