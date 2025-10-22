<?php

namespace app\common\enum\video;

class VideoCategoryEnum
{
    // 分类类型
    const TYPE_SHORT = 'short';      // 短视频
    const TYPE_DRAMA = 'drama';      // 短剧
    const TYPE_LIVE = 'live';        // 直播

    /**
     * 获取分类类型字典
     * @return array
     */
    public static function getTypeDict(): array
    {
        return [
            self::TYPE_SHORT => '短视频',
            self::TYPE_DRAMA => '短剧',
            self::TYPE_LIVE => '直播',
        ];
    }

    /**
     * 获取分类类型描述
     * @param string $type
     * @return string
     */
    public static function getTypeDesc($type): string
    {
        $dict = self::getTypeDict();
        return $dict[$type] ?? '未知';
    }



    // 是否显示
    const SHOW_NO = 0;               // 隐藏
    const SHOW_YES = 1;              // 显示
    /**
     * 获取显示状态字典
     * @return array
     */
    public static function getShowDict(): array
    {
        return [
            self::SHOW_NO => '隐藏',
            self::SHOW_YES => '显示',
        ];
    }

    /**
     * 获取显示状态描述
     * @param int $show
     * @return string
     */
    public static function getShowDesc(int $show): string
    {
        $dict = self::getShowDict();
        return $dict[$show] ?? '未知';
    }
}
