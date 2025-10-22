<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;

use app\common\enum\store\TblStoreCouponEnum;


class TblStoreCouponModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_store_coupon';

    


    // 优惠券类型描述获取器
    public function getCouponTypeDescAttr($value, $data)
    {
        return TblStoreCouponEnum::getStoreCouponTypeDesc($data['coupon_type']);
    }

    // 优惠券领取类型描述获取器
    public function getClaimTypeDescAttr($value, $data)
    {
        return TblStoreCouponEnum::getStoreCouponClaimTypeDesc($data['claim_type']);
    }

    
    // 优惠券状态描述获取器
    public function getStatusDescAttr($value, $data)
    {
        return TblStoreCouponEnum::getStatusDesc($data['status']);
    }


    /**
     * 优惠券生效时间获取器
     */
    public function getStartTimeAttr($value, $data)
    {
        return $this->formatTime($data['start_time']);
    }

    /**
     * 优惠券失效时间获取器
     */
    public function getEndTimeAttr($value, $data)
    {
        return $this->formatTime($data['end_time']);
    }

    // 最小消费金额获取器
    public function getMinSpendAttr($value, $data)
    {
        return $this->formatPrice($data['min_spend']);
    }

    // 最大优惠金额获取器
    public function getDiscountValueAttr($value, $data)
    {
        return $this->formatPrice($data['discount_value']);
    }

}
