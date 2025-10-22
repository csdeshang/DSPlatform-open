<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;

use app\common\enum\order\TblOrderRefundEnum;

class TblOrderRefundLogModel extends BaseModel
{

    // 表名
    protected $name = 'tbl_order_refund_log';




    /**
     * 退款状态获取器
     */
    public function getRefundStatusDescAttr($value, $data)
    {
        return TblOrderRefundEnum::getRefundStatusDesc($data['refund_status']);
    }
}
