<?php
namespace app\adminapi\controller\admin\validate;

use app\deshang\base\BaseValidate;

class AdminRole extends BaseValidate
{

    protected $rule = [
        'id' => 'require|integer',
        'name' => 'require|max:20',
        'desc' => 'max:255',
        'sort' => 'integer|between:0,255',
        'rules' => 'array',
    ];

    protected $message = [
        'id.require' => '角色ID必填',
        'id.integer' => '角色ID必须是数字',
        'name.require' => '角色名必填',
        'name.max' => '角色名长度不能超过20',
        'desc.max' => '角色描述长度不能超过255',
        'sort.integer' => '排序必须为整数',
        'sort.between' => '排序必须在0到255之间',
        'rules.array' => '权限规则必须是数组格式',
    ];

    protected $scene = [
        'create' => ['name', 'desc', 'sort'],
        'update' => ['id', 'name', 'desc', 'sort'],
        'updateRules' => ['id', 'rules'],
    ];

}