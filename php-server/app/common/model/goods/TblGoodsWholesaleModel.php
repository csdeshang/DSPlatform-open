<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;

class TblGoodsWholesaleModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods_wholesale';



    // 批发价 获取器
    public function getWholesalePriceAttr($value, $data)
    {
        return $this->formatPrice($data['wholesale_price']);
    }
    
    
} 