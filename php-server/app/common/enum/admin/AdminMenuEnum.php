<?php

namespace app\common\enum\admin;

/**
 * 管理员菜单相关
 */
class AdminMenuEnum
{
    
    // 类型 menu directory button
    const TYPE_MENU = 'menu'; // 菜单
    const TYPE_DIRECTORY = 'directory'; // 目录
    const TYPE_BUTTON = 'button'; // 按钮

    // 获取类型列表
    public static function getTypeDict(): array
    {
        return [
            self::TYPE_MENU => '菜单',
            self::TYPE_DIRECTORY => '目录',
            self::TYPE_BUTTON => '按钮',
        ];
    }

    // 获取类型描述
    public static function getTypeDesc($value): string
    {
        $data = self::getTypeDict();
        return $data[$value] ?? '未知类型';
    }




}