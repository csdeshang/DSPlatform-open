<?php

namespace app\deshang\kv\keys;

/**
 * 计数器键管理器
 * 
 * 职责：统一管理所有计数器相关的键值常量
 */
class CounterKeyManager
{
    // ========== 商品相关 ==========
    const GOODS_VIEW_COUNT_KEY = 'counter_goods_view_count_%s';
    const GOODS_SALES_COUNT_KEY = 'counter_goods_sales_count_%s';
    const GOODS_STOCK_KEY = 'counter_goods_stock_%s';

    // ========== 用户相关 ==========
    const USER_LOGIN_COUNT_KEY = 'counter_user_login_count_%s';
    const USER_ORDER_COUNT_KEY = 'counter_user_order_count_%s';

    // ========== 店铺相关 ==========
    const STORE_VIEW_COUNT_KEY = 'counter_store_view_count_%s';
    const STORE_ORDER_COUNT_KEY = 'counter_store_order_count_%s';
}
