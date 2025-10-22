<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;

use app\common\enum\user\UserWithdrawalEnum;


// 用户提现账户表
class UserWithdrawalAccountModel extends BaseModel
{

    // 表名
    protected $name = 'user_withdrawal_account';


    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 账户类型 获取器
    public function getAccountTypeDescAttr($value, $data)
    {
        return UserWithdrawalEnum::getAccountTypeDesc($data['account_type']);
    }







    
}
