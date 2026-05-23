<?php

namespace app\adminapi\controller\distributor\validate;

use app\deshang\base\BaseValidate;

class DistributorLevelValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:20',
        'sort' => 'require|integer|between:0,9999',
        'description' => 'max:255',
        'base_self_ratio' => 'require|float|between:0,100',
        'base_parent1_ratio' => 'require|float|between:0,100',
        'base_parent2_ratio' => 'float|between:0,100',
        'self_single_amount' => 'float|egt:0',
        'self_single_amount_is' => 'integer|between:0,1',
        'self_total_amount' => 'float|egt:0',
        'self_total_amount_is' => 'integer|between:0,1',
        'self_total_count' => 'integer|egt:0',
        'self_total_count_is' => 'integer|between:0,1',
        'parent1_total_amount' => 'float|egt:0',
        'parent1_total_amount_is' => 'integer|between:0,1',
        'parent1_total_count' => 'integer|egt:0',
        'parent1_total_count_is' => 'integer|between:0,1',
        'invite_count' => 'integer|egt:0',
        'invite_count_is' => 'integer|between:0,1',
    ];

    protected $message = [
        'name.require' => '等级名称不能为空',
        'name.max' => '等级名称不能超过20个字符',
        'sort.require' => '级别排序不能为空',
        'sort.integer' => '排序必须为整数',
        'sort.between' => '排序必须在0-9999之间',
        'description.max' => '等级描述不能超过255个字符',
        'base_self_ratio.require' => '自购佣金比例不能为空',
        'base_self_ratio.float' => '自购佣金比例必须为数字',
        'base_self_ratio.between' => '自购佣金比例必须在0-100之间',
        'base_parent1_ratio.require' => '1级佣金比例不能为空',
        'base_parent1_ratio.float' => '1级佣金比例必须为数字',
        'base_parent1_ratio.between' => '1级佣金比例必须在0-100之间',
        'base_parent2_ratio.float' => '2级佣金比例必须为数字',
        'base_parent2_ratio.between' => '2级佣金比例必须在0-100之间',
        'self_single_amount.float' => '自购单笔消费金额必须为数字',
        'self_single_amount.egt' => '自购单笔消费金额必须大于等于0',
        'self_single_amount_is.integer' => '自购单笔开关必须为整数',
        'self_single_amount_is.between' => '自购单笔开关取值无效',
        'self_total_amount.float' => '自购总消费金额必须为数字',
        'self_total_amount.egt' => '自购总消费金额必须大于等于0',
        'self_total_amount_is.integer' => '自购总金额开关必须为整数',
        'self_total_amount_is.between' => '自购总金额开关取值无效',
        'self_total_count.integer' => '自购消费次数必须为整数',
        'self_total_count.egt' => '自购消费次数必须大于等于0',
        'self_total_count_is.integer' => '自购次数开关必须为整数',
        'self_total_count_is.between' => '自购次数开关取值无效',
        'parent1_total_amount.float' => '一级分销订单金额必须为数字',
        'parent1_total_amount.egt' => '一级分销订单金额必须大于等于0',
        'parent1_total_amount_is.integer' => '一级分销订单金额开关必须为整数',
        'parent1_total_amount_is.between' => '一级分销订单金额开关取值无效',
        'parent1_total_count.integer' => '一级分销订单数必须为整数',
        'parent1_total_count.egt' => '一级分销订单数必须大于等于0',
        'parent1_total_count_is.integer' => '一级分销订单数开关必须为整数',
        'parent1_total_count_is.between' => '一级分销订单数开关取值无效',
        'invite_count.integer' => '邀请注册人数必须为整数',
        'invite_count.egt' => '邀请注册人数必须大于等于0',
        'invite_count_is.integer' => '邀请人数开关必须为整数',
        'invite_count_is.between' => '邀请人数开关取值无效',
    ];

    protected $scene = [
        'create' => [
            'name', 'sort', 'description',
            'base_self_ratio', 'base_parent1_ratio', 'base_parent2_ratio',
            'self_single_amount', 'self_single_amount_is',
            'self_total_amount', 'self_total_amount_is',
            'self_total_count', 'self_total_count_is',
            'parent1_total_amount', 'parent1_total_amount_is',
            'parent1_total_count', 'parent1_total_count_is',
            'invite_count', 'invite_count_is',
        ],
        'update' => [
            'name', 'sort', 'description',
            'base_self_ratio', 'base_parent1_ratio', 'base_parent2_ratio',
            'self_single_amount', 'self_single_amount_is',
            'self_total_amount', 'self_total_amount_is',
            'self_total_count', 'self_total_count_is',
            'parent1_total_amount', 'parent1_total_amount_is',
            'parent1_total_count', 'parent1_total_count_is',
            'invite_count', 'invite_count_is',
        ],
    ];
}
