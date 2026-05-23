<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;
use app\common\enum\order\TblOrderInvoiceEnum;

class TblOrderInvoiceLogModel extends BaseModel
{
    protected $name = 'tbl_order_invoice_log';

    public function getInvoiceStatusDescAttr($value, $data)
    {
        return TblOrderInvoiceEnum::getInvoiceStatusDesc((int) ($data['invoice_status'] ?? TblOrderInvoiceEnum::STATUS_PENDING));
    }
}
