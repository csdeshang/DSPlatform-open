<?php

namespace app\adminapi\controller\tblOrder\validate;

use app\common\enum\order\TblOrderInvoiceEnum;
use app\deshang\base\BaseValidate;

class TblOrderInvoiceValidate extends BaseValidate
{
    protected $rule = [
        'invoice_id' => 'require|integer|gt:0',
        'platform' => 'max:20',
        'invoice_status' => 'checkInvoiceStatus',
        'order_id' => 'integer|gt:0',
        'order_sn' => 'max:64',
        'username' => 'max:64',
        'user_id' => 'integer|gt:0',
        'store_id' => 'integer|gt:0',
        'store_name' => 'max:64',
        'merchant_id' => 'integer|gt:0',
        'merchant_name' => 'max:64',
        'goods_name' => 'max:128',
    ];

    protected $message = [
        'invoice_id.require' => '开票申请ID不能为空',
        'invoice_status.checkInvoiceStatus' => '开票状态值不正确',
    ];

    protected $scene = [
        'pages' => [
            'platform',
            'invoice_status',
            'order_id',
            'order_sn',
            'username',
            'user_id',
            'store_id',
            'store_name',
            'merchant_id',
            'merchant_name',
            'goods_name',
        ],
        'info' => ['invoice_id'],
    ];

    public function checkInvoiceStatus($value, $rule, $data): bool
    {
        if ($value === '' || $value === null) {
            return true;
        }
        if (!is_numeric($value)) {
            return false;
        }

        return array_key_exists((int) $value, TblOrderInvoiceEnum::getAllInvoiceStatusDict());
    }
}
