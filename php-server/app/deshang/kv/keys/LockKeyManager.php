<?php

namespace app\deshang\kv\keys;


/**
 * 锁键管理器
 * 
 * 职责：统一管理所有分布式锁相关的键值常量
 */
class LockKeyManager
{
    // 通用锁标签（可选，用于 Cache::tag）
    const LOCK_TAG = 'lock';

    // ========== 支付锁 ==========
    const LOCK_API_TRADE_PAY_KEY = 'lock_api_trade_pay_%s';

    // ========== 用户资产锁 ==========
    const LOCK_USER_BALANCE_KEY = 'lock_user_balance_%s';
    const LOCK_USER_POINTS_KEY = 'lock_user_points_%s';
    const LOCK_USER_GROWTH_KEY = 'lock_user_growth_%s';

    // ========== 商户资产锁 ==========
    const LOCK_MERCHANT_BALANCE_KEY = 'lock_merchant_balance_%s';

    // ========== 骑手资产锁 ==========
    const LOCK_RIDER_BALANCE_KEY = 'lock_rider_balance_%s';

    // ========== 技师资产锁 ==========
    const LOCK_TECHNICIAN_BALANCE_KEY = 'lock_technician_balance_%s';

    // ========== 分销员资产锁 ==========
    const LOCK_DISTRIBUTOR_BALANCE_KEY = 'lock_distributor_balance_%s';

    // ========== 队列任务锁 ==========
    const LOCK_QUEUE_TASK_KEY = 'lock:queue:task:%s';
}
