<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;
use app\common\enum\order\TblOrderRefundEnum;
use app\common\model\user\UserModel;
use app\common\model\store\TblStoreModel;
use app\common\model\order\TblOrderRefundLogModel;
use app\common\model\order\TblOrderModel;

/**
 * 订单退款模型
 */
class TblOrderRefundModel extends BaseModel
{
    // 表名
    protected $name = 'tbl_order_refund';




    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 关联退款日志
    public function orderRefundLogList()
    {
        return $this->hasMany(TblOrderRefundLogModel::class, 'refund_id', 'id');
    }

    // 关联订单
    public function order()
    {
        return $this->hasOne(TblOrderModel::class, 'id', 'order_id');
    }










    /**
     * 退款类型获取器
     */
    public function getRefundTypeDescAttr($value, $data)
    {
        return TblOrderRefundEnum::getRefundTypeDesc($data['refund_type']);
    }

    /**
     * 退款状态获取器
     */
    public function getRefundStatusDescAttr($value, $data)
    {
        return TblOrderRefundEnum::getRefundStatusDesc($data['refund_status']);
    }

    /**
     * 退款方式获取器
     */
    public function getRefundMethodDescAttr($value, $data)
    {
        return TblOrderRefundEnum::getRefundMethodDesc($data['refund_method']);
    }

    /**
     * 申请金额获取器
     */
    public function getApplyAmountAttr($value, $data)
    {
        return $this->formatPrice($data['apply_amount']);
    }

    /**
     * 退款金额获取器
     */
    public function getRefundAmountAttr($value, $data)
    {
        return $this->formatPrice($data['refund_amount']);
    }


    /**
     * 同意时间获取器
     */
    public function getAgreeTimeAttr($value, $data)
    {
        return $this->formatTime($data['agree_time']);
    }


    /**
     * 拒绝时间获取器
     */
    public function getRejectTimeAttr($value, $data)
    {
        return $this->formatTime($data['reject_time']);
    }

    /**
     * 退款成功时间获取器
     */
    public function getSuccessTimeAttr($value, $data)
    {
        return $this->formatTime($data['success_time']);
    }



}
