<?php

namespace app\common\enum\goods;

/**
 * 商品相关常量定义
 */
class TblGoodsEnum
{
    // 商品状态
    const STATUS_SHELVED = 1; // 上架
    const STATUS_UNSHELVED = 0; // 下架

    // 获取商品状态字典
    public static function getGoodsStatusDict(): array
    {
        return [
            self::STATUS_SHELVED => '上架',
            self::STATUS_UNSHELVED => '下架',
        ];
    }
    // 获取商品状态描述
    public static function getGoodsStatusDesc($value): string
    {
        $data = self::getGoodsStatusDict();
        return $data[$value] ?? '未知状态';
    }

    // 系统状态
    const SYS_STATUS_PENDING_REVIEW = 0; // 系统状态：待审核
    const SYS_STATUS_NORMAL = 1; // 系统状态：正常
    const SYS_STATUS_FAILED = 2; // 系统状态：审核失败
    const SYS_STATUS_VIOLATED = 3; // 系统状态：违规下架



    // 获取系统状态字典
    public static function getSysStatusDict(): array
    {
        return [
            self::SYS_STATUS_PENDING_REVIEW => '待审核',
            self::SYS_STATUS_NORMAL => '正常',
            self::SYS_STATUS_FAILED => '审核失败',
            self::SYS_STATUS_VIOLATED => '违规下架',
        ];
    }

    // 获取系统状态描述
    public static function getSysStatusDesc($value): string
    {
        $data = self::getSysStatusDict();
        return $data[$value] ?? '未知系统状态';
    }

    // 系统推荐状态
    const SYS_RECOMMEND_STATUS_REC = 1; // 系统推荐状态：推荐
    const SYS_RECOMMEND_STATUS_NOT_REC = 0; // 系统推荐状态：不推荐

    // 获取系统推荐状态字典
    public static function getSysRecommendStatusDict(): array
    {
        return [
            self::SYS_RECOMMEND_STATUS_REC => '推荐',
            self::SYS_RECOMMEND_STATUS_NOT_REC => '不推荐',
        ];
    }
    // 获取系统推荐状态描述
    public static function getSysRecommendStatusDesc($value): string
    {
        $data = self::getSysRecommendStatusDict();
        return $data[$value] ?? '未知系统推荐状态';
    }
}