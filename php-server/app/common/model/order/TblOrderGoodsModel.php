<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;
use app\common\enum\goods\TblGoodsPromotionEnum;


/**
 * 订单商品模型
 */
class TblOrderGoodsModel extends BaseModel
{
    // 表名
    protected $name = 'tbl_order_goods';
    

    
    /**
     * 活动价格获取器
     * 
     * @param mixed $value 原始值
     * @param array $data 所有数据
     * @return string 格式化后的价格
     */
    public function getActivityPriceAttr($value, $data)
    {
        return $this->formatPrice($value);
    }
    
    /**
     * SKU价格获取器
     * 
     * @param mixed $value 原始值
     * @param array $data 所有数据
     * @return string 格式化后的价格
     */
    public function getSkuPriceAttr($value, $data)
    {
        return $this->formatPrice($value);
    }
    
    /**
     * 支付价格获取器
     * 
     * @param mixed $value 原始值
     * @param array $data 所有数据
     * @return string 格式化后的价格
     */
    public function getPayPriceAttr($value, $data)
    {
        return $this->formatPrice($value);
    }

    // promotion_type_desc 获取器
    public function getPromotionTypeDescAttr($value, $data)
    {
        return TblGoodsPromotionEnum::getPromotionTypeDesc($data['promotion_type']);
    }
    

    

}