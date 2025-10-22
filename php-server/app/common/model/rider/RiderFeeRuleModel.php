<?php

namespace app\common\model\rider;

use app\deshang\base\BaseModel;
use app\common\enum\rider\RiderFeeRuleEnum;

class RiderFeeRuleModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'rider_fee_rule';



    
    // 基础费用格式化获取器
    public function getBaseFeeAttr($value, $data)
    {
        return $this->formatPrice($data['base_fee']);
    }
    
} 