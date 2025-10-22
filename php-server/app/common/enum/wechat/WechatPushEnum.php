<?php

namespace app\common\enum\wechat;

/**
 * 微信推送消息日志表
 */
class WechatPushEnum
{
    // 类别
    const TYPE_OFFICIAL = 'official'; // 公众号
    const TYPE_MINI = 'mini'; // 小程序

    // 获取类别字典
    public static function getTypeDict(): array
    {
        return [
            self::TYPE_OFFICIAL => '公众号',
            self::TYPE_MINI => '小程序',
        ];
    }

    // 获取类别描述
    public static function getTypeDesc($value): string
    {
        $data = self::getTypeDict();
        return $data[$value] ?? '未知类别';
    }

}