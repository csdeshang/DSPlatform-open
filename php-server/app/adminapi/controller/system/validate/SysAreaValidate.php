<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysAreaValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'pid' => 'require|integer|egt:0', // 父级ID，必须是整数，且大于等于0
        'name' => 'require|max:100', // 名称，必填，最大长度100
        'sort' => 'integer|egt:0', // 排序，必须是整数，且大于等于0
        'longitude' => 'regex:/^-?\d{1,3}\.\d{1,6}$/', // 经度，正则表达式验证
        'latitude' => 'regex:/^-?\d{1,3}\.\d{1,6}$/', // 纬度，正则表达式验证
        'is_show' => 'boolean', // 是否显示，必须是布尔值
    ];

    // 定义验证提示
    protected $message = [
        'pid.require' => '父级ID不能为空',
        'pid.integer' => '父级ID必须是整数',
        'pid.egt' => '父级ID必须大于等于0',
        'name.require' => '地区名称不能为空',
        'name.max' => '地区名称不能超过100个字符',
        'sort.integer' => '排序必须是整数',
        'sort.egt' => '排序必须大于等于0',
        'longitude.regex' => '经度格式不正确',
        'latitude.regex' => '纬度格式不正确',
        'is_show.boolean' => '是否显示必须是布尔值',
    ];

    // 定义场景
    protected $scene = [
        'create' => ['pid', 'name', 'sort', 'longitude', 'latitude', 'is_show'], // 创建场景
        'update' => ['pid', 'name', 'sort', 'longitude', 'latitude', 'is_show'], // 更新场景
        'info' => ['id'], // 获取信息场景
    ];
}
