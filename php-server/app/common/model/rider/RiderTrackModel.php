<?php

namespace app\common\model\rider;

use app\deshang\base\BaseModel;

class RiderTrackModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'rider_track';

    // 关联骑手
    public function rider()
    {
        return $this->hasOne(RiderModel::class, 'id', 'rider_id');
    }
}
