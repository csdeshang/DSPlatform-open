<?php

namespace app\common\enum\pointsGoods;

/**
 * 积分商品相关枚举
 */
class PointsGoodsEnum
{
    // 商品状态
    const GOODS_STATUS_UNSHELVED = 0; // 下架
    const GOODS_STATUS_SHELVED = 1; // 上架

    // 获取商品状态字典
    public static function getGoodsStatusDict(): array
    {
        return [
            self::GOODS_STATUS_UNSHELVED => '下架',
            self::GOODS_STATUS_SHELVED => '上架',
        ];
    }

    // 获取商品状态描述
    public static function getGoodsStatusDesc($value): string
    {
        $data = self::getGoodsStatusDict();
        return $data[$value] ?? '未知状态';
    }

    // 是否热门
    const IS_HOT_NO = 0; // 否
    const IS_HOT_YES = 1; // 是

    // 获取是否热门字典
    public static function getIsHotDict(): array
    {
        return [
            self::IS_HOT_NO => '否',
            self::IS_HOT_YES => '是',
        ];
    }

    // 获取是否热门描述
    public static function getIsHotDesc($value): string
    {
        $data = self::getIsHotDict();
        return $data[$value] ?? '未知状态';
    }

    // 是否推荐
    const IS_RECOMMEND_NO = 0; // 否
    const IS_RECOMMEND_YES = 1; // 是

    // 获取是否推荐字典
    public static function getIsRecommendDict(): array
    {
        return [
            self::IS_RECOMMEND_NO => '否',
            self::IS_RECOMMEND_YES => '是',
        ];
    }

    // 获取是否推荐描述
    public static function getIsRecommendDesc($value): string
    {
        $data = self::getIsRecommendDict();
        return $data[$value] ?? '未知状态';
    }

    // 是否新品
    const IS_NEW_NO = 0; // 否
    const IS_NEW_YES = 1; // 是

    // 获取是否新品字典
    public static function getIsNewDict(): array
    {
        return [
            self::IS_NEW_NO => '否',
            self::IS_NEW_YES => '是',
        ];
    }

    // 获取是否新品描述
    public static function getIsNewDesc($value): string
    {
        $data = self::getIsNewDict();
        return $data[$value] ?? '未知状态';
    }

    // 删除状态
    const IS_DELETED_NO = 0; // 未删除
    const IS_DELETED_YES = 1; // 已删除

    // 获取删除状态字典
    public static function getIsDeletedDict(): array
    {
        return [
            self::IS_DELETED_NO => '未删除',
            self::IS_DELETED_YES => '已删除',
        ];
    }

    // 获取删除状态描述
    public static function getIsDeletedDesc($value): string
    {
        $data = self::getIsDeletedDict();
        return $data[$value] ?? '未知状态';
    }
}
