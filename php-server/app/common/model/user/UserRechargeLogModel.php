<?php

namespace app\common\model\user;

use app\deshang\base\BaseModel;

use app\common\enum\user\UserRechargeEnum;

use app\common\enum\trade\TradePaymentConfigEnum;


class UserRechargeLogModel extends BaseModel
{

    // 表名
    protected $name = 'user_recharge_log';


    // 关联用户表
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }




    // 支付渠道 获取器
    public function getPayChannelDescAttr($value, $data)
    {
        return TradePaymentConfigEnum::getPaymentChannelDesc($data['pay_channel']);
    }


    // 支付场景 获取器
    public function getPaySceneDescAttr($value, $data)
    {
        return TradePaymentConfigEnum::getPaymentSceneDesc($data['pay_scene']);
    }


    // 充值状态 获取器
    public function getRechargeStatusDescAttr($value, $data)
    {
        return $data['recharge_status'] == 0 ? '待支付' : '已支付';
    }





    // 充值金额 获取器
    public function getRechargeAmountAttr($value, $data)
    {
        return $this->formatPrice($data['recharge_amount']);
    }

    

    /**
     * 支付时间获取器
     */
    public function getPayTimeAttr($value, $data)
    {
        return $this->formatTime($data['pay_time']);
    }






}