<?php

namespace app\common\model\technician;

use app\deshang\base\BaseModel;
use app\common\model\order\TblOrderModel;

class TechnicianScheduleModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'technician_schedule';

    // 关联师傅
    public function technician()
    {
        return $this->hasOne(TechnicianModel::class, 'id', 'technician_id');
    }
    
    // 关联订单
    public function order()
    {
        return $this->hasOne(TblOrderModel::class, 'id', 'order_id');
    }
    

} 