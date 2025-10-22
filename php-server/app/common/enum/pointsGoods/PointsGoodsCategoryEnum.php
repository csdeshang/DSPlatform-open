<?php

namespace app\common\enum\pointsGoods;

/**
 * 积分商品分类相关枚举
 */
class PointsGoodsCategoryEnum
{
    // 是否显示
    const IS_SHOW_NO = 0; // 隐藏
    const IS_SHOW_YES = 1; // 显示

    // 获取是否显示字典
    public static function getIsShowDict(): array
    {
        return [
            self::IS_SHOW_NO => '隐藏',
            self::IS_SHOW_YES => '显示',
        ];
    }

    // 获取是否显示描述
    public static function getIsShowDesc($value): string
    {
        $data = self::getIsShowDict();
        return $data[$value] ?? '未知状态';
    }

    // 父级分类ID
    const PARENT_ID_ROOT = 0; // 顶级分类
}
