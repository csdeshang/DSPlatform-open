<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;

use app\common\enum\user\UserGrowthEnum;


// 用户成长值记录表
class UserGrowthLogModel extends BaseModel
{

    // 表名
    protected $name = 'user_growth_log';


    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 变动类型 获取器
    public function getChangeTypeDescAttr($value, $data)
    {
        return UserGrowthEnum::getChangeTypeDesc($data['change_type']);
    }

    // 变动方式 获取器
    public function getChangeModeDescAttr($value, $data)
    {
        return UserGrowthEnum::getChangeModeDesc($data['change_mode']);
    }






    
}
