<?php

namespace app\deshang\service\trade\validate;

use think\Validate;
use app\common\enum\trade\TradePaymentConfigEnum;

class TradePaymentConfigValidator extends Validate
{
    // 定义验证规则
    protected $rule = [
        'merchant_id'     => 'require|integer',
        'payment_channel' => 'require|checkPaymentChannel',
        'payment_scene'   => 'require|checkPaymentScene',
        'is_enabled'      => 'require|in:0,1',
        'sort'            => 'integer',
        'create_at'      => 'require|integer',
        'update_at'      => 'integer',
    ];

    // 定义错误信息
    protected $message = [
        'merchant_id.require'     => '商户ID不能为空',
        'merchant_id.integer'     => '商户ID必须为整数',
        'payment_channel.require' => '支付渠道不能为空',
        'payment_channel.checkPaymentChannel' => '支付渠道必须是有效的渠道',
        'payment_scene.require'   => '支付场景不能为空',
        'payment_scene.checkPaymentScene' => '支付场景必须是有效的场景',
        'is_enabled.require'      => '是否启用不能为空',
        'is_enabled.in'           => '是否启用必须是 0 或 1',
        'sort.integer'            => '排序必须为整数',

    ];

    // 定义场景
    protected $scene = [
        'create' => ['merchant_id', 'payment_channel', 'payment_scene', 'is_enabled', 'sort'],
        'update' => ['id', 'merchant_id', 'payment_channel', 'payment_scene', 'is_enabled', 'sort'],
    ];

    // 自定义验证方法：检查支付渠道
    protected function checkPaymentChannel($value, $rule, $data = [])
    {
        $validChannels = array_keys(TradePaymentConfigEnum::getPaymentChannelDict());
        return in_array($value, $validChannels);
    }

    // 自定义验证方法：检查支付场景
    protected function checkPaymentScene($value, $rule, $data = [])
    {
        $validScenes = array_keys(TradePaymentConfigEnum::getPaymentSceneDict());
        return in_array($value, $validScenes);
    }
}