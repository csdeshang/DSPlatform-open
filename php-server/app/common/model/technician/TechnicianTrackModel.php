<?php

namespace app\common\model\technician;

use app\deshang\base\BaseModel;

class TechnicianTrackModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'technician_track';

    // 关联师傅
    public function technician()
    {
        return $this->hasOne(TechnicianModel::class, 'id', 'technician_id');
    }
}
