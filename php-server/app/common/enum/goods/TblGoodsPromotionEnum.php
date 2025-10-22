<?php

namespace app\common\enum\goods;

/**
 * 商品促销相关（商品级促销类型） 店铺级促销类型及系统级促销类型，不在此范围
 */
class TblGoodsPromotionEnum
{

    // 促销类型
    const PROMOTION_TYPE_FLASHSALE = 'flashsale'; // 限时折扣
    const PROMOTION_TYPE_WHOLESALE = 'wholesale'; // 批发
    const PROMOTION_TYPE_MEMBER_DISCOUNT = 'member_discount'; // 店铺会员折扣
    const PROMOTION_TYPE_USER_DISCOUNT = 'user_discount'; // 平台会员折扣


    // [店铺级] 满减   优惠券  
    // [平台级] seckill 秒杀 主要是平台级， 因为每个时间点需要秒杀活动  


    // 获取促销类型字典
    public static function getPromotionTypeDict(): array
    {
        return [
            self::PROMOTION_TYPE_FLASHSALE => '限时折扣',
            self::PROMOTION_TYPE_WHOLESALE => '批发',
            self::PROMOTION_TYPE_MEMBER_DISCOUNT => '店铺会员折扣',
            self::PROMOTION_TYPE_USER_DISCOUNT => '平台会员折扣',
        ];
    }

    // 获取促销类型描述
    public static function getPromotionTypeDesc($value): string
    {
        $data = self::getPromotionTypeDict();
        return $data[$value] ?? '商品原价';
    }

    








}