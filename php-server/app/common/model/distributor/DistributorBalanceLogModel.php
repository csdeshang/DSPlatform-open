<?php

namespace app\common\model\distributor;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\enum\distributor\DistributorBalanceEnum;

class DistributorBalanceLogModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'distributor_balance_log';


    // 关联分销商
    public function distributorUser()
    {
        return $this->hasOne(UserModel::class, 'id', 'distributor_user_id');
    }
    


    
    // 变动类型获取器
    public function getChangeTypeDescAttr($value, $data)
    {
        return DistributorBalanceEnum::getChangeTypeDesc($data['change_type']);
    }
    
    // 变动方式获取器
    public function getChangeModeDescAttr($value, $data)
    {
        return DistributorBalanceEnum::getChangeModeDesc($data['change_mode']);
    }
    
    // 变动金额获取器
    public function getChangeAmountAttr($value, $data)
    {
        return $this->formatPrice($data['change_amount']);
    }
    
    // 交易前余额获取器
    public function getBeforeBalanceAttr($value, $data)
    {

        return $this->formatPrice($data['before_balance']);
    }
    
    // 交易后余额获取器
    public function getAfterBalanceAttr($value, $data)
    {

        return $this->formatPrice($data['after_balance']);
    }

}