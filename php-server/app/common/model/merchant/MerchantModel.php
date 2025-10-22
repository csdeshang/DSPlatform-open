<?php

namespace app\common\model\merchant;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\enum\merchant\MerchantEnum;


class MerchantModel extends BaseModel
{

    // 表名
    protected $name = 'merchant';


    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    
    // 金额获取器
    public function getBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['balance']);
    }

    // 金额获取器
    public function getBalanceInAttr($value, $data)
    {
        return $this->formatPrice($data['balance_in']);
    }

    // 金额获取器
    public function getBalanceOutAttr($value, $data)
    {
        return $this->formatPrice($data['balance_out']);
    }


    // 审核状态获取器
    public function getApplyStatusDescAttr($value, $data)
    {
        return MerchantEnum::getApplyStatusDesc($data['apply_status']);
    }


    // 审核时间获取器
    public function getApplyTimeAttr($value, $data)
    {
        return $this->formatTime($data['apply_time']);
    }

    // 审核时间获取器
    public function getAuditTimeAttr($value, $data)
    {
        return $this->formatTime($data['audit_time']);
    }



    
}
