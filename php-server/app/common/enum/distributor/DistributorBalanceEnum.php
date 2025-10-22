<?php

namespace app\common\enum\distributor;

/**
 * 分销员余额相关
 */
class DistributorBalanceEnum
{

    // 变动类型
    const TYPE_ORDER_COMMISSION  = 'order_commission'; // 订单佣金
    const TYPE_SYSTEM = 'system'; // 系统
    const TYPE_DISTRIBUTOR_TO_USER = 'distributor_to_user'; // 佣金转至余额(分销佣金金额转入用户余额)
    // const TYPE_USER_TO_DISTRIBUTOR = 'user_to_distributor'; // 用户转账(用户转至分销员)



    // 获取变动类型列表
    public static function getChangeTypeDict(): array
    {
        return [
            self::TYPE_ORDER_COMMISSION => '订单佣金',
            self::TYPE_DISTRIBUTOR_TO_USER => '转至余额',
            // self::TYPE_USER_TO_DISTRIBUTOR => '用户转账',
            self::TYPE_SYSTEM => '系统',
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
