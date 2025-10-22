<?php

namespace app\common\enum\user;

/**
 * 会员余额相关
 */
class UserBalanceEnum
{
    
    // 变动方式
    const MODE_INCREASE = 1; // 增加
    const MODE_DECREASE = 2; // 减少

    // 变动类型
    const TYPE_RECHARGE = 'recharge'; // 充值
    const TYPE_REFUND = 'refund'; // 退款
    const TYPE_ORDER = 'order'; // 订单
    const TYPE_SYSTEM = 'system'; // 系统
    const TYPE_COMMISSION = 'commission'; // 佣金
    const TYPE_WITHDRAWAL = 'withdrawal'; // 提现申请扣除
    const TYPE_WITHDRAWAL_REJECT = 'withdrawal_reject'; // 提现拒绝退回余额
    const TYPE_MERCHANT_TO_USER = 'merchant_to_user'; // 商户转账(商户转至用户)
    // const TYPE_USER_TO_MERCHANT = 'user_to_merchant'; // 用户转账(用户转至商户)
    const TYPE_DISTRIBUTOR_TO_USER = 'distributor_to_user'; // 佣金转入(分销佣金转入用户余额)
    // const TYPE_USER_TO_DISTRIBUTOR = 'user_to_distributor'; // 用户转账(用户转至分销商)
    const TYPE_RIDER_TO_USER = 'rider_to_user'; // 骑手转账(骑手转至用户)
    // const TYPE_USER_TO_RIDER = 'user_to_rider'; // 用户转账(用户转至骑手)
    const TYPE_TECHNICIAN_TO_USER = 'technician_to_user'; // 师傅转账(师傅转至用户)
    // const TYPE_USER_TO_TECHNICIAN = 'user_to_technician'; // 用户转账(用户转至师傅)


    // 获取变动类型列表
    public static function getChangeTypeDict(): array
    {
        return [
            self::TYPE_RECHARGE => '充值',
            self::TYPE_REFUND => '退款',
            self::TYPE_ORDER => '订单',
            self::TYPE_SYSTEM => '系统',
            self::TYPE_COMMISSION => '佣金',
            self::TYPE_WITHDRAWAL => '提现',
            self::TYPE_WITHDRAWAL_REJECT => '提现退回',
            self::TYPE_MERCHANT_TO_USER => '商户转账',
            // self::TYPE_USER_TO_MERCHANT => '用户转账',
            self::TYPE_DISTRIBUTOR_TO_USER => '佣金转入',   
            // self::TYPE_USER_TO_DISTRIBUTOR => '用户转账',
            self::TYPE_RIDER_TO_USER => '骑手转账',
            // self::TYPE_USER_TO_RIDER => '用户转账',
            self::TYPE_TECHNICIAN_TO_USER => '师傅转账',
            // self::TYPE_USER_TO_TECHNICIAN => '用户转账',
        ];
    }

    // 获取变动类型描述
    public static function getChangeTypeDesc($value): string
    {
        $data = self::getChangeTypeDict();
        return $data[$value] ?? '未知类型';
    }


    // 获取变动方式列表
    public static function getChangeModeDict(): array
    {
        return [
            self::MODE_INCREASE => '增加',
            self::MODE_DECREASE => '减少',
        ];
    }
    // 获取变动方式描述
    public static function getChangeModeDesc($value): string
    {
        $data = self::getChangeModeDict();
        return $data[$value] ?? '未知方式';
    }


}