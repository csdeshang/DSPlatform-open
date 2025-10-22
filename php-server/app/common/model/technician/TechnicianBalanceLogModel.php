<?php

namespace app\common\model\technician;

use app\deshang\base\BaseModel;
use app\common\enum\technician\TechnicianBalanceEnum;

class TechnicianBalanceLogModel extends BaseModel
{
    protected $name = 'technician_balance_log';

    // 关联师傅
    public function technician()
    {
        return $this->hasOne(TechnicianModel::class, 'id', 'technician_id');
    }


    
    
    // 变动类型获取器
    public function getChangeTypeDescAttr($value, $data)
    {
        return TechnicianBalanceEnum::getChangeTypeDesc($data['change_type']);
    }
    
    // 变动方式获取器
    public function getChangeModeDescAttr($value, $data)
    {
        return TechnicianBalanceEnum::getChangeModeDesc($data['change_mode']);
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
