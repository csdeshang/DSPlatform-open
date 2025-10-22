<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;
use app\common\model\goods\TblGoodsModel;

class TblGoodsFavoritesModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods_favorites';


    // 定义与商品模型的关系
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }


}