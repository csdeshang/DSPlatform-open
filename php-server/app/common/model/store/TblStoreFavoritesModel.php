<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;
use app\common\model\store\TblStoreModel;

class TblStoreFavoritesModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_store_favorites';


    // 定义与店铺模型的关系
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }


}