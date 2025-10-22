<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;

use app\common\enum\user\UserPointsEnum;


// 用户积分记录表
class UserPointsLogModel extends BaseModel
{

    // 表名
    protected $name = 'user_points_log';


    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 变动类型 获取器
    public function getChangeTypeDescAttr($value, $data)
    {
        return UserPointsEnum::getChangeTypeDesc($data['change_type']);
    }

    // 变动方式 获取器
    public function getChangeModeDescAttr($value, $data)
    {
        return UserPointsEnum::getChangeModeDesc($data['change_mode']);
    }






    
}
