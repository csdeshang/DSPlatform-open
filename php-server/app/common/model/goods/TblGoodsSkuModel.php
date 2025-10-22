<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;

class TblGoodsSkuModel extends BaseModel
{


    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods_sku';

    // 关联商品
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }


    // 价格 获取器
    public function getSkuPriceAttr($value, $data)
    {
        return $this->formatPrice($data['sku_price']);
    }

    // market_price 获取器
    public function getMarketPriceAttr($value, $data)
    {
        return $this->formatPrice($data['market_price']);
    }
    
    // cost_price 获取器
    public function getCostPriceAttr($value, $data)
    {
        return $this->formatPrice($data['cost_price']);
    }
    
    


}