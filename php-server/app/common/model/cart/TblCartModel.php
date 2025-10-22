<?php

namespace app\common\model\cart;

use app\deshang\base\BaseModel;
use app\common\model\goods\TblGoodsModel;
use app\common\model\user\UserModel;
use app\common\model\goods\TblGoodsSkuModel;
use app\common\model\store\TblStoreModel;

use app\common\enum\goods\TblGoodsPromotionEnum;


class TblCartModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_cart';

    // 定义与商品模型的关系
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }

    // 定义与用户模型的关系
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 定义与 SKU 模型的关系
    public function goodsSku()
    {
        return $this->hasOne(TblGoodsSkuModel::class, 'id', 'sku_id');
    }

    // 定义与店铺模型的关系
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }


    // promotion_price 获取器
    public function getPromotionPriceAttr($value, $data)
    {
        return $this->formatPrice($data['promotion_price']);
    }


    // 促销类型 获取器
    public function getPromotionTypeDescAttr($value, $data)
    {
        return TblGoodsPromotionEnum::getPromotionTypeDesc($data['promotion_type']);
    }


}