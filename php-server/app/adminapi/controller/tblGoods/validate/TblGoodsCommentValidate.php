<?php

namespace app\adminapi\controller\tblGoods\validate;

use app\deshang\base\BaseValidate;

/**
 * 商品评论验证器
 */
class TblGoodsCommentValidate extends BaseValidate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        'platform' => 'alphaNum',
        'user_id' => 'alphaNum',
        'goods_id' => 'alphaNum',
        'store_id' => 'alphaNum',
        'order_id' => 'alphaNum',
        'is_show' => 'in:0,1',
        'is_reply' => 'in:0,1',
        'is_anonymous' => 'in:0,1',
        'id' => 'require|integer|gt:0',
        'field' => 'require|in:is_show,is_reply,is_anonymous',
    ];

    /**
     * 验证消息
     * @var array
     */
    protected $message = [
        'platform.alphaNum' => '平台类型格式错误',
        'user_id.alphaNum' => '用户ID格式错误',
        'goods_id.alphaNum' => '商品ID格式错误',
        'store_id.alphaNum' => '店铺ID格式错误',
        'order_id.alphaNum' => '订单ID格式错误',
        'is_show.in' => '显示状态值错误',
        'is_reply.in' => '回复状态值错误',
        'is_anonymous.in' => '匿名状态值错误',
        'id.require' => '评论ID不能为空',
        'id.integer' => '评论ID必须为整数',
        'id.gt' => '评论ID必须大于0',
        'field.require' => '字段名不能为空',
        'field.in' => '字段名不在允许范围内',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'pages' => ['platform', 'user_id', 'goods_id', 'store_id', 'order_id', 'is_show', 'is_reply', 'is_anonymous'],
        'toggle' => ['id', 'field'],
    ];
} 