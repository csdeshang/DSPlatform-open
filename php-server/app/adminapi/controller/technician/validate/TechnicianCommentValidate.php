<?php

namespace app\adminapi\controller\technician\validate;

use app\deshang\base\BaseValidate;

class TechnicianCommentValidate extends BaseValidate
{
    protected $rule = [

        'id' => 'require|integer',
        'field' => 'require|in:is_show,is_reply',
    ];

    protected $message = [

        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须为整数',
        'field.require' => '字段名不能为空',
        'field.in' => '字段名不正确',
    ];

    protected $scene = [
        'toggle' => ['id', 'field'],
    ];
} 