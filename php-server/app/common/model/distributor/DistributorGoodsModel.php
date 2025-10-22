<?php

namespace app\common\model\distributor;

use app\deshang\base\BaseModel;

class DistributorGoodsModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'distributor_goods';


    // goods_self_amount 获取器 
    public function getGoodsSelfAmountAttr($value, $data)
    {
        return $this->formatPrice($data['goods_self_amount']);
    }

    // goods_parent1_amount 获取器 
    public function getGoodsParent1AmountAttr($value, $data)
    {
        return $this->formatPrice($data['goods_parent1_amount']);
    }

    // goods_parent2_amount 获取器 
    public function getGoodsParent2AmountAttr($value, $data)
    {
        return $this->formatPrice($data['goods_parent2_amount']);
    }
    


}