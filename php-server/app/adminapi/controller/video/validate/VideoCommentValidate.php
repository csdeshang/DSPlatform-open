<?php

namespace app\adminapi\controller\video\validate;

use app\deshang\base\BaseValidate;

class VideoCommentValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'field' => 'require|max:50',
    ];

    protected $message = [
        'id.require' => '评论ID不能为空',
        'id.integer' => '评论ID必须为整数',
        'id.gt' => '评论ID必须大于0',
        'field.require' => '字段名不能为空',
        'field.max' => '字段名长度不能超过50个字符',
    ];

    protected $scene = [
        'toggle' => ['id', 'field'],
    ];
} 