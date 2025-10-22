<?php

namespace app\common\enum\system;

/**
 * 系统平台相关常量定义
 */
class SysPlatformEnum
{

    // 应用场景sys系统级,比如交友 店铺级store
    const SCENE_SYS = 'sys';
    const SCENE_STORE = 'store';

    // 获取应用场景字典
    public static function getSceneDict(): array
    {
        return [
            self::SCENE_SYS => '系统级',
            self::SCENE_STORE => '店铺级',
        ];
    }

    // 获取应用场景描述
    public static function getSceneDesc($value): string
    {
        $data = self::getSceneDict();
        return $data[$value] ?? '未知场景';
    }


}