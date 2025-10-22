<?php

namespace app\common\model\merchant;

use app\deshang\base\BaseModel;
use app\common\enum\merchant\MerchantBalanceEnum;

/**
 * 商户余额日志模型
 */
class MerchantBalanceLogModel extends BaseModel
{
    // 表名
    protected $name = 'merchant_balance_log';

    // 关联商户
    public function merchant()
    {
        return $this->hasOne(MerchantModel::class, 'id', 'merchant_id');
    }
    
    // 变动类型获取器
    public function getChangeTypeDescAttr($value, $data)
    {
        return MerchantBalanceEnum::getChangeTypeDesc($data['change_type']);
    }
    
    // 变动方式获取器
    public function getChangeModeDescAttr($value, $data)
    {
        return MerchantBalanceEnum::getChangeModeDesc($data['change_mode']);
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
