<?php

namespace app\common\enum\kms;

/**
 * KMS课时相关枚举
 */
class KmsLessonEnum
{
    // 课时类型
    const LESSON_TYPE_VIDEO = 1;  // 视频
    const LESSON_TYPE_TEXT = 2;   // 图文
    const LESSON_TYPE_AUDIO = 3;  // 音频

    /**
     * 获取课时类型字典
     * 
     * @return array 课时类型字典
     */
    public static function getLessonTypeDict(): array
    {
        return [
            self::LESSON_TYPE_VIDEO => '视频',
            self::LESSON_TYPE_TEXT => '图文',
            self::LESSON_TYPE_AUDIO => '音频'
        ];
    }

    /**
     * 获取课时类型描述
     * 
     * @param int $type 课时类型
     * @return string 课时类型描述
     */
    public static function getLessonTypeDesc(int $type): string
    {
        $dict = self::getLessonTypeDict();
        return $dict[$type] ?? '未知类型';
    }

    // 是否免费
    const IS_FREE_NO = 0;   // 收费
    const IS_FREE_YES = 1;  // 免费

    /**
     * 获取是否免费字典
     * 
     * @return array 是否免费字典
     */
    public static function getIsFreeDict(): array
    {
        return [
            self::IS_FREE_NO => '收费',
            self::IS_FREE_YES => '免费'
        ];
    }

    /**
     * 获取是否免费描述
     * 
     * @param int $isFree 是否免费
     * @return string 是否免费描述
     */
    public static function getIsFreeDesc(int $isFree): string
    {
        $dict = self::getIsFreeDict();
        return $dict[$isFree] ?? '未知';
    }
}