<?php

namespace app\adminapi\controller\tblGoods\validate;


use app\deshang\base\BaseValidate;


class TblGoodsValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'platform' => 'require|max:50|checkPlatform', // 平台名称，必填，最大长度50，使用自定义验证方法
    ];

    // 定义验证提示
    protected $message = [
        'platform.require' => '平台名称不能为空',
        'platform.max' => '平台名称不能超过50个字符',
        'platform.checkPlatform' => '平台名称无效,请确认是否安装', // 自定义验证错误提示
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['platform'], 

    ];


}
