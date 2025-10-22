<?php

namespace app\common\model\rider;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\enum\rider\RiderEnum;

class RiderModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'rider';




    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }
    
    



    
    // 骑手状态描述获取器
    public function getStatusDescAttr($value, $data)
    {
        return RiderEnum::getRiderStatusDesc($data['status']);
    }
    
    // 骑手审核状态描述获取器
    public function getApplyStatusDescAttr($value, $data)
    {
        return RiderEnum::getApplyStatusDesc($data['apply_status']);
    }
    
    // 是否可用描述获取器
    public function getIsEnabledDescAttr($value, $data)
    {
        return $data['is_enabled'] ? '启用' : '禁用';
    }
    
    // 骑手余额获取器
    public function getBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['balance']);
    }
    
    // 骑手收入总额获取器
    public function getBalanceInAttr($value, $data)
    {
        return $this->formatPrice($data['balance_in']);
    }
    
    // 骑手支出总额获取器
    public function getBalanceOutAttr($value, $data)
    {
        return $this->formatPrice($data['balance_out']);
    }
    
    // 骑手审核时间获取器
    public function getAuditTimeAttr($value, $data)
    {
        return $this->formatTime($data['audit_time']);
    }
    
    // 骑手位置更新时间
    public function getRiderLocTimeAttr($value, $data)
    {
        return $this->formatTime($data['rider_loc_time']);
    }
    

}