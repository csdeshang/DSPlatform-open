<?php

namespace app\adminapi\controller\wechat\validate;

use app\deshang\base\BaseValidate;

class WechatMiniSettingValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'app_id' => 'require|alphaDash', // 公众号AppID，必填，字母数字下划线
        'app_secret' => 'require|alphaDash', // 公众号AppSecret，必填，字母数字下划线
        
    ];

    // 定义验证提示
    protected $message = [
        'app_id.require' => '公众号AppID不能为空',
        'app_id.alphaDash' => '公众号AppID只能包含字母、数字和下划线',
        'app_secret.require' => '公众号AppSecret不能为空',
        'app_secret.alphaDash' => '公众号AppSecret只能包含字母、数字和下划线',
    ];

    // 定义场景
    protected $scene = [
        'update' => ['app_id', 'app_secret'], // 更新场景
    ];
}
