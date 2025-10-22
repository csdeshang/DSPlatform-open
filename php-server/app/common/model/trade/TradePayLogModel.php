<?php

namespace app\common\model\trade;

use app\deshang\base\BaseModel;

use app\common\enum\trade\TradePayEnum;

class TradePayLogModel extends BaseModel{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'trade_pay_log';


    // 支付状态 获取器
    public function getPayStatusDescAttr($value, $data)
    {
        return TradePayEnum::getPayStatusDesc($data['pay_status']);
    }
    

    // 来源类型 获取器
    public function getSourceTypeDescAttr($value, $data)
    {
        return TradePayEnum::getSourceTypeDesc($data['source_type']);
    }


    /**
     * 支付时间获取器
     */
    public function getPayTimeAttr($value, $data)
    {
        return $this->formatTime($data['pay_time']);
    }


    /**
     * 关闭时间获取器
     */
    public function getCloseTimeAttr($value, $data)
    {
        return $this->formatTime($data['close_time']);
    }

    // 支付金额 获取器
    public function getPayAmountAttr($value, $data)
    {
        return $this->formatPrice($data['pay_amount']);
    }
    

}
