<?php

namespace app\common\enum\pointsGoods;

/**
 * 积分商品评价相关枚举
 */
class PointsGoodsEvaluateEnum
{
    // 评价分数
    const EVALUATE_SCORE_1 = 1; // 1星
    const EVALUATE_SCORE_2 = 2; // 2星
    const EVALUATE_SCORE_3 = 3; // 3星
    const EVALUATE_SCORE_4 = 4; // 4星
    const EVALUATE_SCORE_5 = 5; // 5星

    // 是否匿名
    const IS_ANONYMOUS_NO = 0; // 否
    const IS_ANONYMOUS_YES = 1; // 是

    // 是否显示
    const IS_SHOW_NO = 0; // 隐藏
    const IS_SHOW_YES = 1; // 显示

    // 获取评价分数字典
    public static function getEvaluateScoreDict(): array
    {
        return [
            self::EVALUATE_SCORE_1 => '1星',
            self::EVALUATE_SCORE_2 => '2星',
            self::EVALUATE_SCORE_3 => '3星',
            self::EVALUATE_SCORE_4 => '4星',
            self::EVALUATE_SCORE_5 => '5星',
        ];
    }

    // 获取评价分数描述
    public static function getEvaluateScoreDesc($value): string
    {
        $data = self::getEvaluateScoreDict();
        return $data[$value] ?? '未知评分';
    }

    // 获取是否匿名字典
    public static function getIsAnonymousDict(): array
    {
        return [
            self::IS_ANONYMOUS_NO => '否',
            self::IS_ANONYMOUS_YES => '是',
        ];
    }

    // 获取是否匿名描述
    public static function getIsAnonymousDesc($value): string
    {
        $data = self::getIsAnonymousDict();
        return $data[$value] ?? '未知状态';
    }

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
}
