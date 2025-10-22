<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;

/**
 * 订单财务模型
 * 
 * 管理订单财务数据
 */
class TblOrderFinanceModel extends BaseModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $name = 'tbl_order_finance';
    


    // 订单实付金额格式化获取器
    public function getPayAmountAttr($value, $data)
    {
        return $this->formatPrice($data['pay_amount']);
    }

    // 分销金额格式化获取器
    public function getDistributorAmountAttr($value, $data)
    {
        return $this->formatPrice($data['distributor_amount']);
    }

    // 店铺平台抽成金额格式化获取器
    public function getStoreSysAmountAttr($value, $data)
    {
        return $this->formatPrice($data['store_sys_amount']);
    }

    // 店铺实际收入格式化获取器 
    public function getStoreAmountAttr($value, $data)
    {
        return $this->formatPrice($data['store_amount']);
    }

    // 骑手实际收入格式化获取器
    public function getRiderAmountAttr($value, $data)
    {
        return $this->formatPrice($data['rider_amount']);
    }

    // 骑手平台抽成金额格式化获取器
    public function getRiderSysAmountAttr($value, $data)
    {
        return $this->formatPrice($data['rider_sys_amount']);
    }

    // 师傅服务费总额 格式化获取器
    public function getTechnicianAmountAttr($value, $data)
    {
        return $this->formatPrice($data['technician_amount']);
    }

    // 师傅服务费  格式化获取器
    public function getTechnicianServiceFeeAttr($value, $data)
    {
        return $this->formatPrice($data['technician_service_fee']);
    }

    // 师傅路程费 格式化获取器
    public function getTechnicianTripFeeAttr($value, $data)
    {
        return $this->formatPrice($data['technician_trip_fee']);
    }
    

    // 退款金额格式化获取器
    public function getRefundAmountAttr($value, $data)
    {
        return $this->formatPrice($data['refund_amount']);
    }


    // 结算时间格式化获取器
    public function getSettleTimeAttr($value, $data)
    {
        return $this->formatTime($data['settle_time']);
    }




}
