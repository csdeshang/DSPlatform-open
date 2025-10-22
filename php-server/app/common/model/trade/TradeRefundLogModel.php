<?php

namespace app\common\model\trade;

use app\deshang\base\BaseModel;


class TradeRefundLogModel extends BaseModel{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'trade_refund_log';



    /**
     * 退款时间获取器
     */
    public function getRefundTimeAttr($value, $data)
    {
        return $this->formatTime($data['refund_time']);
    }


    /**
     * 关闭时间获取器
     */
    public function getCloseTimeAttr($value, $data)
    {
        return $this->formatTime($data['close_time']);
    }
    

}
