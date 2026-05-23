<?php

namespace app\adminapi\controller\wechat\validate;

use app\deshang\base\BaseValidate;

class WechatWebSettingValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'app_id' => 'require|alphaDash', // 网站应用AppID，必填，字母数字下划线
        'app_secret' => 'require|alphaDash', // 网站应用AppSecret，必填，字母数字下划线
        
    ];

    // 定义验证提示
    protected $message = [
        'app_id.require' => '网站应用AppID不能为空',
        'app_id.alphaDash' => '网站应用AppID只能包含字母、数字和下划线',
        'app_secret.require' => '网站应用AppSecret不能为空',
        'app_secret.alphaDash' => '网站应用AppSecret只能包含字母、数字和下划线',
    ];

    // 定义场景
    protected $scene = [
        'update' => ['app_id', 'app_secret'], // 更新场景
    ];
}
