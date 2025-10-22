<?php

namespace app\common\model\technician;

use app\deshang\base\BaseModel;
use app\common\model\goods\TblGoodsModel;

class TechnicianGoodsRelModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'technician_goods_rel';

    // 关联师傅
    public function technician()
    {
        return $this->hasOne(TechnicianModel::class, 'id', 'technician_id');
    }
    
    // 关联服务商品
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }
    


} 