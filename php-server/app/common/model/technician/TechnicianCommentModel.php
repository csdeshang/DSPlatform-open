<?php

namespace app\common\model\technician;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\model\order\TblOrderModel;
use app\common\model\technician\TechnicianModel;

class TechnicianCommentModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'technician_comment';

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

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

    // 回复时间获取器
    public function getReplyTimeAttr($value, $data)
    {
        return $this->formatTime($data['reply_time']);
    }


}
