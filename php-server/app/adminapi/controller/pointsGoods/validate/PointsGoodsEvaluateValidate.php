<?php

namespace app\adminapi\controller\pointsGoods\validate;

use app\deshang\base\BaseValidate;

class PointsGoodsEvaluateValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // 评价ID，必填，必须是大于0的整数
        'user_id' => 'integer|gt:0', // 用户ID，必须是大于0的整数
        'goods_id' => 'integer|gt:0', // 商品ID，必须是大于0的整数
        'order_id' => 'integer|gt:0', // 订单ID，必须是大于0的整数
        'is_show' => 'in:0,1', // 显示状态，必须是0或1
        'is_anonymous' => 'in:0,1', // 匿名状态，必须是0或1
        'field' => 'require|in:is_show,is_anonymous', // 字段名，必须是is_show或is_anonymous
        'reply_content' => 'require|max:500', // 回复内容，必填，最大长度500
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '评价ID不能为空',
        'id.integer' => '评价ID必须是整数',
        'id.gt' => '评价ID必须大于0',
        'user_id.integer' => '用户ID必须是整数',
        'user_id.gt' => '用户ID必须大于0',
        'goods_id.integer' => '商品ID必须是整数',
        'goods_id.gt' => '商品ID必须大于0',
        'order_id.integer' => '订单ID必须是整数',
        'order_id.gt' => '订单ID必须大于0',
        'is_show.in' => '显示状态必须是0或1',
        'is_anonymous.in' => '匿名状态必须是0或1',
        'field.require' => '字段名不能为空',
        'field.in' => '字段名必须是is_show或is_anonymous',
        'reply_content.require' => '回复内容不能为空',
        'reply_content.max' => '回复内容不能超过500个字符',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['user_id', 'goods_id', 'order_id', 'is_show', 'is_anonymous'], // 分页查询场景
        'toggle' => ['id', 'field'], // 切换字段状态场景
        'reply' => ['id', 'reply_content'], // 回复场景
    ];
}