<?php

namespace app\common\enum\technician;

/**
 * 师傅余额相关
 */
class TechnicianBalanceEnum
{

    // 变动类型
    const TYPE_TECHNICIAN_FEE = 'technician_fee'; // 师傅服务费
    const TYPE_ORDER_BONUS  = 'order_bonus'; // 订单奖励
    const TYPE_PENALTY  = 'penalty'; // 罚款
    // const TYPE_WITHDRAWAL = 'withdrawal'; // 提现
    const TYPE_SYSTEM = 'system'; // 系统
    const TYPE_TECHNICIAN_TO_USER = 'technician_to_user'; // 师傅转账至用户

    // 获取变动类型列表
    public static function getChangeTypeDict(): array
    {
        return [
            self::TYPE_TECHNICIAN_FEE => '师傅服务费',
            self::TYPE_ORDER_BONUS => '订单奖励',
            self::TYPE_PENALTY => '罚款',
            // self::TYPE_WITHDRAWAL => '提现',
            self::TYPE_SYSTEM => '系统',
            self::TYPE_TECHNICIAN_TO_USER => '师傅端转账',
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
