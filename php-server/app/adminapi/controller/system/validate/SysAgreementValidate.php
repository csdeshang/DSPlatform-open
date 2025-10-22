<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysAgreementValidate extends BaseValidate
{
    protected $rule = [
        'title' => 'require|max:100',
        'content' => 'require',
        'sort' => 'integer|between:0,999',
        'is_show' => 'in:0,1',
    ];

    protected $message = [
        'title.require' => '协议标题不能为空',
        'title.max' => '协议标题不能超过100个字符',
        'content.require' => '协议内容不能为空',
        'sort.integer' => '排序必须为整数',
        'sort.between' => '排序值必须在0-999之间',
        'is_show.in' => '显示状态只能为0或1',
    ];

    protected $scene = [
        'update' => ['title', 'content', 'sort', 'is_show'],
    ];
} 