<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;
use app\common\enum\order\TblOrderInvoiceEnum;
use app\common\model\store\TblStoreModel;
use app\common\model\user\UserModel;

class TblOrderInvoiceModel extends BaseModel
{
    protected $name = 'tbl_order_invoice';

    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    public function orderInvoiceLogList()
    {
        return $this->hasMany(TblOrderInvoiceLogModel::class, 'invoice_id', 'id');
    }

    public function getInvoiceStatusDescAttr($value, $data)
    {
        return TblOrderInvoiceEnum::getInvoiceStatusDesc((int) ($data['invoice_status'] ?? TblOrderInvoiceEnum::STATUS_PENDING));
    }

    public function getInvoiceAmountAttr($value, $data)
    {
        return $this->formatPrice($data['invoice_amount']);
    }

    /** 驳回时间 */
    public function getRejectTimeAttr($value, $data)
    {
        return $this->formatTime($data['reject_time'] ?? 0);
    }

    /** 开票完成时间 */
    public function getIssueTimeAttr($value, $data)
    {
        return $this->formatTime($data['issue_time'] ?? 0);
    }

    /** 作废时间 */
    public function getVoidTimeAttr($value, $data)
    {
        return $this->formatTime($data['void_time'] ?? 0);
    }
}
