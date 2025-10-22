<?php

namespace app\common\enum\goods;


// 店铺级限时折扣
class TblGoodsFlashsaleEnum
{

    // 状态
    const STATUS_NOT_START = 0; // 未开始
    const STATUS_START = 1; // 进行中
    const STATUS_END = 2; // 已结束
    const STATUS_CLOSE = 3; // 已关闭

    // 获取商品状态字典
    public static function getStatusDict(): array
    {
        return [
            self::STATUS_NOT_START => '未开始',
            self::STATUS_START => '进行中',
            self::STATUS_END => '已结束',
            self::STATUS_CLOSE => '已关闭',
        ];
    }
    // 获取商品状态描述
    public static function getStatusDesc($value): string
    {
        $data = self::getStatusDict();
        return $data[$value] ?? '未知状态';
    }



}