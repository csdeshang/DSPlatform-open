<?php

namespace app\adminapi\controller\user\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\user\UserWithdrawalEnum;

class UserWithdrawalValidate extends BaseValidate
{

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'transfer_type' => 'require|checkTransferType',
        'transfer_remark' => 'max:255',
        'status' => 'require|checkStatus',
        'operation_remark' => 'max:255',
    ];

    protected $message = [
        'id.require' => '提现记录ID不能为空',
        'id.integer' => '提现记录ID必须是整数',
        'id.gt' => '提现记录ID必须大于0',
        'transfer_type.require' => '转账类型不能为空',
        'transfer_type.checkTransferType' => '转账类型值不正确',
        'transfer_remark.max' => '转账备注不能超过255个字符',
        'status.require' => '状态不能为空',
        'status.checkStatus' => '状态值不正确',
        'operation_remark.max' => '操作备注不能超过255个字符',
    ];

    protected $scene = [
        'operation' => ['id', 'transfer_type', 'transfer_remark', 'status', 'operation_remark']
    ];


    public function checkTransferType($value, $rule, $data)
    {
        return array_key_exists($value, UserWithdrawalEnum::getTransferTypeDict());
    }

    public function checkStatus($value, $rule, $data)
    {
        return array_key_exists($value, UserWithdrawalEnum::getStatusDict());
    }



}
