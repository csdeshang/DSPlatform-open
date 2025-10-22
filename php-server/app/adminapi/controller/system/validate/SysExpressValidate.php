<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysExpressValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|number|gt:0',
        'code' => 'require|max:20',
        'name' => 'require|max:20',
        'logo' => 'max:255',
        'url' => 'url|max:100',
        'sort' => 'number',
        'is_show' => 'in:0,1'
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '物流ID不能为空',
        'id.number' => '物流ID必须为数字',
        'id.gt' => '物流ID必须大于0',
        'code.require' => '物流公司编号不能为空',
        'code.max' => '物流公司编号不能超过20个字符',
        'name.require' => '物流名称不能为空',
        'name.max' => '物流名称不能超过20个字符',
        'logo.max' => 'logo路径不能超过255个字符',
        'url.url' => '链接必须是有效的URL地址',
        'url.max' => '链接不能超过100个字符',
        'sort.number' => '排序必须为数字',
        'is_show.in' => '是否显示必须为0或1'
    ];

    // 定义场景
    protected $scene = [
        'info' => ['id'],
        'create' => ['code', 'name', 'logo', 'url', 'sort', 'is_show'],
        'update' => ['id', 'code', 'name', 'logo', 'url', 'sort', 'is_show']
    ];
    
}
