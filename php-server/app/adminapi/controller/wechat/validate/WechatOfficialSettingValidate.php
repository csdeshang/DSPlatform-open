<?php

namespace app\adminapi\controller\wechat\validate;

use app\deshang\base\BaseValidate;

class WechatOfficialSettingValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'app_id' => 'require|alphaDash', // 公众号AppID，必填，字母数字下划线
        'app_secret' => 'require|alphaDash', // 公众号AppSecret，必填，字母数字下划线
        'token' => 'alphaDash', // 公众号Token，字母数字下划线
        'encoding_aes_key' => 'alphaDash', // 消息加密密钥，字母数字下划线
        'encryption_type' => 'in:plain,compatible,safe', // 加密类型
    ];

    // 定义验证提示
    protected $message = [
        'app_id.require' => '公众号AppID不能为空',
        'app_id.alphaDash' => '公众号AppID只能包含字母、数字和下划线',
        'app_secret.require' => '公众号AppSecret不能为空',
        'app_secret.alphaDash' => '公众号AppSecret只能包含字母、数字和下划线',
        'token.alphaDash' => '公众号Token只能包含字母、数字和下划线',
        'encoding_aes_key.alphaDash' => '消息加密密钥只能包含字母、数字和下划线',
        'encryption_type.in' => '加密类型必须是plain、compatible或safe其中之一',
    ];

    // 定义场景
    protected $scene = [
        'update' => ['app_id', 'app_secret', 'token', 'encoding_aes_key', 'encryption_type'], // 更新场景
    ];
}
