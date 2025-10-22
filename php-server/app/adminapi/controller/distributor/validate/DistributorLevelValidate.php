<?php

namespace app\adminapi\controller\distributor\validate;

use app\deshang\base\BaseValidate;

class DistributorLevelValidate extends BaseValidate
{
    protected $rule = [
        'level_name' => 'require|max:32',
        'level_sort' => 'integer|between:0,9999',
        'level_one_rate' => 'float|between:0,100',
        'level_two_rate' => 'float|between:0,100',
        'level_three_rate' => 'float|between:0,100',
        'level_money' => 'float|egt:0',
        'level_buy_times' => 'integer|egt:0',
        'level_buy_money' => 'float|egt:0',
        'level_down_num' => 'integer|egt:0',
        'level_down_money' => 'float|egt:0',
    ];

    protected $message = [
        'level_name.require' => '等级名称不能为空',
        'level_name.max' => '等级名称不能超过32个字符',
        'level_sort.integer' => '排序必须为整数',
        'level_sort.between' => '排序必须在0-9999之间',
        'level_one_rate.float' => '一级佣金比例必须为数字',
        'level_one_rate.between' => '一级佣金比例必须在0-100之间',
        'level_two_rate.float' => '二级佣金比例必须为数字',
        'level_two_rate.between' => '二级佣金比例必须在0-100之间',
        'level_three_rate.float' => '三级佣金比例必须为数字',
        'level_three_rate.between' => '三级佣金比例必须在0-100之间',
        'level_money.float' => '升级金额必须为数字',
        'level_money.egt' => '升级金额必须大于等于0',
        'level_buy_times.integer' => '购买次数必须为整数',
        'level_buy_times.egt' => '购买次数必须大于等于0',
        'level_buy_money.float' => '购买金额必须为数字',
        'level_buy_money.egt' => '购买金额必须大于等于0',
        'level_down_num.integer' => '下级人数必须为整数',
        'level_down_num.egt' => '下级人数必须大于等于0',
        'level_down_money.float' => '下级消费金额必须为数字',
        'level_down_money.egt' => '下级消费金额必须大于等于0',
    ];

    protected $scene = [
        'create' => ['level_name', 'level_sort', 'level_one_rate', 'level_two_rate', 'level_three_rate', 'level_money', 'level_buy_times', 'level_buy_money', 'level_down_num', 'level_down_money'],
        'update' => ['level_name', 'level_sort', 'level_one_rate', 'level_two_rate', 'level_three_rate', 'level_money', 'level_buy_times', 'level_buy_money', 'level_down_num', 'level_down_money'],
    ];
} 