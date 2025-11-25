<?php

namespace app\deshang\kv\keys;

/**
 * 限流键管理器
 * 
 * 职责：统一管理所有限流相关的键值常量
 */
class RateLimitKeyManager
{
    // ========== 用户限流 ==========
    const USER_KEY = 'rate_limit_user_%s';
    const USER_PAY_KEY = 'rate_limit_user_pay_%s';
    const USER_API_KEY = 'rate_limit_user_api_%s';

    // ========== IP限流 ==========
    const IP_KEY = 'rate_limit_ip_%s';
    const IP_API_KEY = 'rate_limit_ip_api_%s';

    // ========== 业务限流 ==========
    const PAY_KEY = 'rate_limit_pay_%s';
    const LOGIN_KEY = 'rate_limit_login_%s';
    const REGISTER_KEY = 'rate_limit_register_%s';
}
