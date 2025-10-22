<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;

use app\common\enum\user\UserBalanceEnum;


// 用户存款记录表
class UserBalanceLogModel extends BaseModel
{

    // 表名
    protected $name = 'user_balance_log';


    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 变动类型 获取器
    public function getChangeTypeDescAttr($value, $data)
    {
        return UserBalanceEnum::getChangeTypeDesc($data['change_type']);
    }

    // 变动方式 获取器
    public function getChangeModeDescAttr($value, $data)
    {
        return UserBalanceEnum::getChangeModeDesc($data['change_mode']);
    }


    // 变动金额获取器（格式化显示）
    public function getChangeAmountAttr($value, $data)
    {
        return $this->formatPrice($data['change_amount']);
    }
    
    // 交易前余额获取器（格式化显示）
    public function getBeforeBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['before_balance']);
    }
    
    // 交易后余额获取器（格式化显示）
    public function getAfterBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['after_balance']);
    }



    
}
