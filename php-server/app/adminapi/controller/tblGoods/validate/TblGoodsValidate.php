<?php

namespace app\adminapi\controller\tblGoods\validate;


use app\deshang\base\BaseValidate;


class TblGoodsValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'platform' => 'require|max:50|checkPlatform', // 平台名称，必填，最大长度50，使用自定义验证方法
        'category_id' => 'integer|egt:0',
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '商品ID不能为空',
        'id.integer' => '商品ID必须是整数',
        'id.gt' => '商品ID必须大于0',
        'platform.require' => '平台名称不能为空',
        'platform.max' => '平台名称不能超过50个字符',
        'platform.checkPlatform' => '平台名称无效,请确认是否安装', // 自定义验证错误提示
        'category_id.integer' => '商品分类ID必须是整数',
        'category_id.egt' => '商品分类ID不能小于0',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['platform', 'category_id'],
        'softDelete' => ['id'],
        'restore' => ['id'],
    ];


}
