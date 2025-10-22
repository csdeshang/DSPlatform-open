<?php

namespace app\common\enum\wechat;

/**
 * 微信订阅相关常量定义  wechat_subscribe_record 表
 */
class WechatSubscribeEnum
{
    // 订阅类型
    const SUBSCRIBE_TYPE_MINI = 'mini'; // 小程序订阅
    const SUBSCRIBE_TYPE_OFFICIAL = 'official'; // 公众号订阅
    
    // 获取订阅类型字典
    public static function getSubscribeTypeDict(): array
    {
        return [
            self::SUBSCRIBE_TYPE_MINI => '小程序订阅',
            self::SUBSCRIBE_TYPE_OFFICIAL => '公众号订阅',
        ];
    }
    
    // 获取订阅类型描述
    public static function getSubscribeTypeDesc($value): string
    {
        $data = self::getSubscribeTypeDict();
        return $data[$value] ?? '未知订阅类型';
    }
    
    // 订阅状态
    const SUBSCRIBE_STATUS_ACCEPT = 'accept'; // 用户同意订阅
    const SUBSCRIBE_STATUS_REJECT = 'reject'; // 用户拒绝订阅
    const SUBSCRIBE_STATUS_BAN = 'ban'; // 模板被禁用
    const SUBSCRIBE_STATUS_FILTER = 'filter'; // 被过滤（如重复订阅等）
    const SUBSCRIBE_STATUS_UNKNOWN = 'unknown'; // 未知错误
    
    // 获取订阅状态字典
    public static function getSubscribeStatusDict(): array
    {
        return [
            self::SUBSCRIBE_STATUS_ACCEPT => '同意订阅',
            self::SUBSCRIBE_STATUS_REJECT => '拒绝订阅',
            self::SUBSCRIBE_STATUS_BAN => '模板被禁用',
            self::SUBSCRIBE_STATUS_FILTER => '订阅被过滤',
            self::SUBSCRIBE_STATUS_UNKNOWN => '未知错误',
        ];
    }
    
    // 获取订阅状态描述
    public static function getSubscribeStatusDesc($value): string
    {
        $data = self::getSubscribeStatusDict();
        return $data[$value] ?? '未知订阅状态';
    }
    
    // 发送状态
    const SEND_STATUS_PENDING = 0; // 未发送
    const SEND_STATUS_SENT = 1; // 已发送
    const SEND_STATUS_FAILED = 2; // 发送失败
    
    // 获取发送状态字典
    public static function getSendStatusDict(): array
    {
        return [
            self::SEND_STATUS_PENDING => '未发送',
            self::SEND_STATUS_SENT => '已发送',
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
