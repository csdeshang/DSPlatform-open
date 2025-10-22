<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;
use app\common\enum\user\UserBehaviorEnum;

/**
 * 用户操作行为记录模型
 */
class UserBehaviorLogModel extends BaseModel
{
    // 表名
    protected $name = 'user_behavior_log';

    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 行为类型获取器
    public function getBehaviorTypeDescAttr($value, $data)
    {
        return UserBehaviorEnum::getBehaviorTypeDesc($data['behavior_type']);
    }

    // 行为状态获取器
    public function getBehaviorStatusDescAttr($value, $data)
    {
        return UserBehaviorEnum::getBehaviorStatusDesc($data['behavior_status']);
    }

    // 风险等级获取器
    public function getRiskLevelDescAttr($value, $data)
    {
        return UserBehaviorEnum::getRiskLevelDesc($data['risk_level']);
    }

    // 异常状态获取器
    public function getIsAbnormalDescAttr($value, $data)
    {
        return UserBehaviorEnum::getAbnormalDesc($data['is_abnormal']);
    }

}
