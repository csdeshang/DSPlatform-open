<?php

namespace app\common\model\technician;

use app\deshang\base\BaseModel;
use app\common\enum\technician\TechnicianEnum;
use app\common\model\store\TblStoreModel;
use app\common\model\user\UserModel;

class TechnicianModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'technician';

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    // 关联师傅商品关系
    public function technicianGoodsRel()
    {
        return $this->hasMany(TechnicianGoodsRelModel::class, 'technician_id', 'id');
    }




    // 师傅状态描述获取器
    public function getTechnicianStatusDescAttr($value, $data)
    {
        return TechnicianEnum::getTechnicianStatusDesc($data['technician_status']);
    }

    // 师傅申请状态描述获取器
    public function getApplyStatusDescAttr($value, $data)
    {
        return TechnicianEnum::getApplyStatusDesc($data['apply_status']);
    }

    // 师傅性别描述获取器
    public function getGenderDescAttr($value, $data)
    {
        return TechnicianEnum::getTechnicianGenderDesc($data['gender']);
    }
    // 师傅余额获取器
    public function getBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['balance']);
    }

    // 师傅收入总额获取器
    public function getBalanceInAttr($value, $data)
    {
        return $this->formatPrice($data['balance_in']);
    }

    // 师傅支出总额获取器
    public function getBalanceOutAttr($value, $data)
    {
        return $this->formatPrice($data['balance_out']);
    }

    // 师傅申请时间获取器
    public function getApplyTimeAttr($value, $data)
    {
        return $this->formatTime($data['apply_time']);
    }

    // 师傅位置更新时间
    public function getTechnicianLocTimeAttr($value, $data)
    {
        return $this->formatTime($data['technician_loc_time']);
    }

}
