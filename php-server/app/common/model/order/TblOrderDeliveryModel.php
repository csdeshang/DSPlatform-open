<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;
use app\common\enum\order\TblOrderDeliveryEnum;
use app\common\enum\order\TblOrderEnum;

use app\common\model\order\TblOrderModel;
use app\common\model\order\TblOrderAddressModel;

class TblOrderDeliveryModel extends BaseModel
{

    // 表名
    protected $name = 'tbl_order_delivery';


    // 订单配送关联
    public function order()
    {
        return $this->hasOne(TblOrderModel::class, 'id', 'order_id');
    }

    // 关联订单地址
    public function orderAddress()
    {
        return $this->hasOne(TblOrderAddressModel::class, 'order_id', 'order_id');
    }

    // 关联订单商品
    public function orderGoodsList()
    {
        return $this->hasMany(TblOrderGoodsModel::class, 'order_id', 'order_id');
    }

    // 关联订单财务
    public function orderFinance()
    {
        return $this->hasOne(TblOrderFinanceModel::class, 'order_id', 'order_id');
    }


    // 交付状态描述获取器
    public function getDeliveryStatusDescAttr($value, $data)
    {
        // 根据交付方式和交付状态，返回对应的描述
        return TblOrderDeliveryEnum::getDeliveryStatusDesc($data['delivery_method'],$data['delivery_status']);
    }

    // 交付方式描述获取器
    public function getDeliveryMethodDescAttr($value, $data)
    {
        return TblOrderEnum::getAllDeliveryDesc($data['delivery_method']);
    }


    // 配送总费用格式化获取器
    public function getRiderTotalFeeAttr($value, $data)
    {
        return $this->formatPrice($data['rider_total_fee']);
    }


    // 骑手费用格式化获取器
    public function getRiderFeeAttr($value, $data)
    {
        return $this->formatPrice($data['rider_fee']);
    }


    // 骑手完成时间格式化获取器
    public function getRiderCompleteTimeAttr($value, $data)
    {
        return $this->formatTime($data['rider_complete_time']);
    }

    // 师傅预约时间格式化获取器
    public function getTechnicianApptTimeAttr($value, $data)
    {
        return $this->formatTime($data['technician_appt_time']);
    }

    // 师傅费用格式化获取器
    public function getTechnicianFeeAttr($value, $data)
    {
        return $this->formatPrice($data['technician_fee']);
    }

}