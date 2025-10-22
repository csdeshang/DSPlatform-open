<?php

namespace app\common\enum\attachment;

/**
 * 附件相关
 */
class AttachmentEnum
{
    
    // 变动方式
    const USER_SCENE_ADMIN = 0; // 管理员
    const USER_SCENE_USER = 1; // 用户

    // 获取变动类型列表
    public static function getUserSceneDict(): array
    {
        return [
            self::USER_SCENE_ADMIN => '管理员',
            self::USER_SCENE_USER => '用户',
        ];
    }

    // 获取变动类型描述
    public static function getUserSceneDesc($value): string
    {
        $data = self::getUserSceneDict();
        return $data[$value] ?? '未知类型';
    }

    // 附件类型
    const ATTACHMENT_TYPE_IMAGE = 'image'; // 图片
    const ATTACHMENT_TYPE_VIDEO = 'video'; // 视频
    const ATTACHMENT_TYPE_FILE = 'file'; // 文件

    // 获取附件类型字典
    public static function getAttachmentTypeDict(): array
    {
        return [
            self::ATTACHMENT_TYPE_IMAGE => '图片',
            self::ATTACHMENT_TYPE_VIDEO => '视频',
            self::ATTACHMENT_TYPE_FILE => '文件',
        ];
    }
    // 获取附件类型描述
    public static function getAttachmentTypeDesc($value): string
    {
        $data = self::getAttachmentTypeDict();
        return $data[$value] ?? '未知类型';
    }


}