<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;

use app\common\model\store\TblStoreCouponModel;
use app\common\enum\store\TblStoreCouponUserEnum;

// 优惠券用户领取记录表
class TblStoreCouponUserModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_store_coupon_user';


    // 关联店铺
    public function storeCoupon()
    {
        return $this->hasOne(TblStoreCouponModel::class, 'id', 'coupon_id');
    }


    /**
     * 使用时间获取器
     */
    public function getUsedTimeAttr($value, $data)
    {
        return $this->formatTime($data['used_time']);
    }


    /**
     * 获取用户优惠券状态
     */
    public function getStatusDescAttr($value, $data)
    {
        return TblStoreCouponUserEnum::getStoreCouponUserStatusDesc($data['status']);
    }


}
