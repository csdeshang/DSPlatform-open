<?php

namespace app\common\enum\merchant;

/**
 * 商户余额相关
 */
class MerchantBalanceEnum
{
    


    // 变动类型
    const TYPE_WITHDRAW = 'withdraw'; // 提现
    const TYPE_REFUND = 'refund'; // 退款
    const TYPE_COMMISSION = 'commission'; // 佣金(增加为 订单退款退回佣金，减少为 佣金扣除)
    const TYPE_ORDER = 'order'; // 订单
    const TYPE_RIDER_FEE = 'rider_fee'; // 骑手配送费
    const TYPE_TECHNICIAN_FEE = 'technician_fee'; // 师傅服务费
    const TYPE_SYSTEM = 'system'; // 系统
    const TYPE_SERVICE_FEE = 'service_fee'; // 平台服务费(当店铺设置了平台服务费，用户确认收货后，会扣除此金额)
    const TYPE_MERCHANT_TO_USER = 'merchant_to_user'; // 商户转账(商户转至用户)
    // const TYPE_USER_TO_MERCHANT = 'user_to_merchant'; // 用户转账(用户转至商户)


    // 获取变动类型列表
    public static function getChangeTypeDict(): array
    {
        return [
            self::TYPE_WITHDRAW => '提现',
            self::TYPE_REFUND => '退款',
            self::TYPE_COMMISSION => '佣金',
            self::TYPE_ORDER => '订单',
            self::TYPE_RIDER_FEE => '骑手配送费',
            self::TYPE_TECHNICIAN_FEE => '师傅服务费',
            self::TYPE_SYSTEM => '系统',
            self::TYPE_SERVICE_FEE => '平台服务费',
            self::TYPE_MERCHANT_TO_USER => '商户转账',
            // self::TYPE_USER_TO_MERCHANT => '用户转账',
        ];
    }

    // 获取变动类型描述
    public static function getChangeTypeDesc($value): string
    {
        $data = self::getChangeTypeDict();
        return $data[$value] ?? '未知类型';
    }

    // 变动方式
    const MODE_INCREASE = 1; // 增加
    const MODE_DECREASE = 2; // 减少
    
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