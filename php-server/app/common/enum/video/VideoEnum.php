<?php

namespace app\common\enum\video;

class VideoEnum
{
    // 内容类型 short/drama/live
    const CONTENT_TYPE_SHORT = 'short'; // 短视频
    const CONTENT_TYPE_DRAMA = 'drama'; // 短剧
    const CONTENT_TYPE_LIVE = 'live'; // 直播

    /**
     * 获取内容类型列表
     * @return array
     */
    public static function getContentTypeDict(): array
    {
        return [
            self::CONTENT_TYPE_SHORT => '短视频',
            self::CONTENT_TYPE_DRAMA => '短剧',
            self::CONTENT_TYPE_LIVE => '直播',

        ];
    }

    /**
     * 获取内容类型描述
     * @param string $value
     * @return string
     */
    public static function getContentTypeDesc($value): string
    {
        $data = self::getContentTypeDict();
        return $data[$value] ?? '未知状态';
    }
    




    
}