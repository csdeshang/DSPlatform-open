<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;
use app\common\model\store\TblStoreAuthUserModel;


class UserModel extends BaseModel
{

    // 表名
    protected $name = 'user';




    // 余额 获取器
    public function getBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['balance']);
    }

    // 余额增加 获取器
    public function getBalanceInAttr($value, $data)
    {
        return $this->formatPrice($data['balance_in']);
    }

    // 余额减少 获取器
    public function getBalanceOutAttr($value, $data)
    {
        return $this->formatPrice($data['balance_out']);
    }

    // 佣金 获取器
    public function getDistributorBalanceAttr($value, $data)
    {
        return $this->formatPrice($data['distributor_balance']);
    }

    // 佣金总收入 获取器
    public function getDistributorBalanceInAttr($value, $data)
    {
        return $this->formatPrice($data['distributor_balance_in']);
    }

    // 佣金总支出 获取器
    public function getDistributorBalanceOutAttr($value, $data)
    {
        return $this->formatPrice($data['distributor_balance_out']);
    }
    
    // 分销商添加时间 获取器
    public function getDistributorAddtimeAttr($value, $data)
    {
        return $this->formatTime($data['distributor_addtime']);
    }
    
    


    /**
     * 登录时间获取器
     */
    public function getLoginTimeAttr($value, $data)
    {
        return $this->formatTime($data['login_time']);
    }


    /**
     * 上次登录时间获取器
     */
    public function getOldLoginTimeAttr($value, $data)
    {
        return $this->formatTime($data['old_login_time']);
    }


    // 生日 获取器
    public function getBirthdayAttr($value, $data)
    {
        return $this->formatTime($data['birthday']);
    }

    
}
