<?php

namespace app\common\enum\trade;


/**
 * 支付配置相关
 */
class TradePaymentConfigEnum
{

    // 支付渠道
    const CHANNEL_BALANCE = 'balance_pay'; // 余额
    const CHANNEL_WECHAT = 'wechat_pay'; // 微信
    const CHANNEL_ALIPAY = 'ali_pay'; // 支付宝
    const CHANNEL_OFFLINE = 'offline_pay'; // 线下付款 (主要用于商户或平台确认收款,其他结算流程一样，退款是余额退款)


    // 支付场景
    const SCENE_H5 = 'h5'; // H5
    const SCENE_PC = 'pc'; // PC
    const SCENE_APP = 'app'; // APP
    const SCENE_WECHAT_OFFICIAL = 'wechat_official'; // 微信官方公众号
    const SCENE_WECHAT_MINI = 'wechat_mini'; // 微信小程序
    const SCENE_DOUYIN_MINI = 'douyin_mini'; // 抖音小程序

    // 是否启用
    const IS_ENABLED_YES = 1; // 启用
    const IS_ENABLED_NO = 0; // 未启用


    // 获取支付场景列表
    public static function getPaymentSceneDict(): array
    {
        return [
            self::SCENE_H5 => 'H5支付',
            self::SCENE_PC => 'PC支付',
            self::SCENE_APP => 'APP支付',
            self::SCENE_WECHAT_OFFICIAL => '微信公众号支付',
            self::SCENE_WECHAT_MINI => '微信小程序支付',
            // self::SCENE_DOUYIN_MINI => '抖音小程序支付',
        ];
    }
    // 获取支付场景描述
    public static function getPaymentSceneDesc($value): string
    {
        $data = self::getPaymentSceneDict();
        return $data[$value] ?? '未知场景';
    }


    // 获取支付渠道列表
    public static function getPaymentChannelDict(): array
    {
        return [
            self::CHANNEL_BALANCE => '余额支付',
            self::CHANNEL_WECHAT => '微信支付',
            self::CHANNEL_ALIPAY => '支付宝支付',
            self::CHANNEL_OFFLINE => '线下付款',
        ];
    }
    // 获取支付渠道描述
    public static function getPaymentChannelDesc($value): string
    {
        $data = self::getPaymentChannelDict();
        return $data[$value] ?? '未知渠道';
    }

    // 获取支付渠道图标
    public static function getPaymentChannelIcon($value): string
    {
        switch ($value) {
            case self::CHANNEL_BALANCE:
                return '/common/payment/balance.png';
            case self::CHANNEL_WECHAT:
                return '/common/payment/wechat.png';
            case self::CHANNEL_ALIPAY:
                return '/common/payment/alipay.png';
            default:
                return '/common/payment/unknown.png';
        }
    }


    // 获取系统支持支付列表
    public static function getSystemSupportPaymentList()
    {
        return [
            // 微信官方公众号
            self::SCENE_WECHAT_OFFICIAL => [self::CHANNEL_BALANCE, self::CHANNEL_WECHAT, self::CHANNEL_ALIPAY],
            // 微信小程序
            self::SCENE_WECHAT_MINI => [self::CHANNEL_BALANCE, self::CHANNEL_WECHAT, self::CHANNEL_ALIPAY],
            // H5
            self::SCENE_H5 => [self::CHANNEL_BALANCE, self::CHANNEL_WECHAT, self::CHANNEL_ALIPAY],
            // PC
            self::SCENE_PC => [self::CHANNEL_BALANCE, self::CHANNEL_WECHAT, self::CHANNEL_ALIPAY],
            // APP
            self::SCENE_APP => [self::CHANNEL_BALANCE, self::CHANNEL_WECHAT, self::CHANNEL_ALIPAY],
            // 抖音小程序
            // self::SCENE_DOUYIN_MINI => [self::CHANNEL_BALANCE, self::CHANNEL_WECHAT, self::CHANNEL_ALIPAY],
        ];
    }
}
