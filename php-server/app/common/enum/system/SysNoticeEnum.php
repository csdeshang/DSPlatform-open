<?php

namespace app\common\enum\system;

/**
 * 系统通知相关常量定义   sys_notice_tpl  sys_notice_log 表
 */
class SysNoticeEnum
{
    // 模板类型
    const TEMPLATE_TYPE_BUSINESS = 1; // 业务通知
    const TEMPLATE_TYPE_VERIFICATION = 2; // 验证码(注册，登录)
    
    // 获取模板类型字典
    public static function getTemplateTypeDict(): array
    {
        return [
            self::TEMPLATE_TYPE_BUSINESS => '业务通知',
            self::TEMPLATE_TYPE_VERIFICATION => '验证码通知',
        ];
    }
    
    // 获取模板类型描述
    public static function getTemplateTypeDesc($value): string
    {
        $data = self::getTemplateTypeDict();
        return $data[$value] ?? '未知模板类型';
    }
    
    // 接收类型
    const RECEIVER_TYPE_USER = 1; // 用户
    const RECEIVER_TYPE_STORE = 2; // 店铺
    
    // 获取接收类型字典
    public static function getReceiverTypeDict(): array
    {
        return [
            self::RECEIVER_TYPE_USER => '用户',
            self::RECEIVER_TYPE_STORE => '店铺',
        ];
    }
    
    // 获取接收类型描述
    public static function getReceiverTypeDesc($value): string
    {
        $data = self::getReceiverTypeDict();
        return $data[$value] ?? '未知接收类型';
    }
    
    // 通知开关状态
    const SWITCH_ON = 1; // 开启
    const SWITCH_OFF = 0; // 关闭
    
    // 获取通知开关状态字典
    public static function getSwitchStatusDict(): array
    {
        return [
            self::SWITCH_ON => '开启',
            self::SWITCH_OFF => '关闭',
        ];
    }
    
    // 获取通知开关状态描述
    public static function getSwitchStatusDesc($value): string
    {
        $data = self::getSwitchStatusDict();
        return $data[$value] ?? '未知开关状态';
    }
    
    // 通知渠道
    const CHANNEL_INTERNAL = 'internal'; // 站内通知
    const CHANNEL_EMAIL = 'email'; // 邮箱通知
    const CHANNEL_SMS = 'sms'; // 短信通知
    const CHANNEL_WECHAT_OFFICIAL = 'wechat_official'; // 微信公众号通知
    const CHANNEL_WECHAT_MINI = 'wechat_mini'; // 微信小程序通知
    
    // 获取通知渠道字典
    public static function getChannelDict(): array
    {
        return [
            self::CHANNEL_INTERNAL => '站内通知',
            self::CHANNEL_EMAIL => '邮箱通知',
            self::CHANNEL_SMS => '短信通知',
            self::CHANNEL_WECHAT_OFFICIAL => '微信公众号通知',
            self::CHANNEL_WECHAT_MINI => '微信小程序通知',
        ];
    }

    // 获取通知渠道描述
    public static function getChannelDesc($value): string
    {
        $data = self::getChannelDict();
        return $data[$value] ?? '未知通知渠道';
    }


    // 发送状态   sys_notice_sms_log 与 sys_notice_log 表 共用
    const SEND_STATUS_SENDING = 0; // 发送中
    const SEND_STATUS_SUCCESS = 1; // 发送成功
    const SEND_STATUS_FAILED = 2; // 发送失败
    
    // 获取发送状态字典
    public static function getSendStatusDict(): array
    {
        return [
            self::SEND_STATUS_SENDING => '发送中',
            self::SEND_STATUS_SUCCESS => '发送成功',
            self::SEND_STATUS_FAILED => '发送失败',
        ];
    }

    // 获取发送状态描述
    public static function getSendStatusDesc($value): string
    {
        $data = self::getSendStatusDict();
        return $data[$value] ?? '未知发送状态';
    }

    
}
