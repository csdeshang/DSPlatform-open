<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;

use app\common\enum\user\UserWithdrawalEnum;


// 用户提现记录表
class UserWithdrawalLogModel extends BaseModel
{

    // 表名
    protected $name = 'user_withdrawal_log';


    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 转账类型 获取器
    public function getTransferTypeDescAttr($value, $data)
    {
        return UserWithdrawalEnum::getTransferTypeDesc($data['transfer_type']);
    }


    // 账户类型 获取器
    public function getAccountTypeDescAttr($value, $data)
    {
        return UserWithdrawalEnum::getAccountTypeDesc($data['account_type']);
    }


    // 提现状态 获取器
    public function getStatusDescAttr($value, $data)
    {
        return UserWithdrawalEnum::getStatusDesc($data['status']);
    }


    /**
     * 操作时间获取器
     */
    public function getOperationTimeAttr($value, $data)
    {
        return $this->formatTime($data['operation_time']);
    }



    // 申请金额 获取器
    public function getApplyAmountAttr($value, $data)
    {
        return $this->formatPrice($data['apply_amount']);
    }


    // 提现金额 获取器
    public function getWithdrawalAmountAttr($value, $data)
    {
        return $this->formatPrice($data['withdrawal_amount']);
    }

    // 手续费 获取器
    public function getFeeAmountAttr($value, $data)
    {
        return $this->formatPrice($data['fee_amount']);
    }
    
    


    
}
