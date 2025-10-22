<?php

namespace app\adminapi\controller\video\validate;

use app\deshang\base\BaseValidate;

class VideoCategoryValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'type' => 'require|in:short,drama,live',
        'pid' => 'integer|>=:0',
        'name' => 'require|max:50',
        'description' => 'max:255',
        'sort' => 'integer|between:0,999',
        'is_show' => 'boolean',
        'id' => 'require|integer|>:0',
    ];

    // 定义验证提示
    protected $message = [
        'type.require' => '分类类型不能为空',
        'type.in' => '分类类型必须为：short、drama、live',
        'pid.integer' => '父级分类ID必须为整数',
        'pid.>=' => '父级分类ID不能小于0',
        'name.require' => '分类名称不能为空',
        'name.max' => '分类名称不能超过50个字符',
        'description.max' => '描述不能超过255个字符',
        'sort.integer' => '排序必须为整数',
        'sort.between' => '排序值必须在0-999之间',
        'is_show.boolean' => '是否显示必须是布尔值',
        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须为整数',
        'id.>' => 'ID必须大于0',
    ];

    // 定义场景
    protected $scene = [
        'create' => ['type', 'pid', 'name', 'description', 'sort', 'is_show'],
        'update' => ['id', 'type', 'pid', 'name', 'description', 'sort', 'is_show'],
        'tree' => ['type'],
    ];
} 