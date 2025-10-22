<?php

namespace app\deshang\utils;

use think\facade\Cache;

/**
 * 处理缓存操作的工具类。
 * 这包装了 ThinkPHP 的 Cache facade，用于常见任务如设置、获取和删除缓存条目。
 * 参考 ThinkPHP 8.0 缓存文档 [ThinkPHP Cache Documentation](https://doc.thinkphp.cn/v8_0/caches.html)，扩展了标签支持、永久存储等功能。
 * 您可以根据需要进一步扩展。
 */
class CacheUtil
{

    // 系统配置相关
    const SYS_CONFIG_TAG = 'sys_config';
    const SYS_CONFIG_KEY = 'sys_config_%s';

    // 系统协议
    const SYS_AGREEMENT_TAG = 'sys_agreement';
    const SYS_AGREEMENT_INFO_KEY = 'sys_agreement_info_%s';

    // 系统地区
    const SYS_AREA_TAG = 'sys_area';
    const SYS_AREA_LIST_KEY = 'sys_area_list_%s';

    // 系统文章
    const SYS_ARTICLE_TAG = 'sys_article';
    const SYS_ARTICLE_LIST_KEY = 'sys_article_list_%s';
    const SYS_ARTICLE_PAGES_KEY = 'sys_article_pages_%s';
    const SYS_ARTICLE_INFO_KEY = 'sys_article_info_%s';

    //系统文字分类
    const SYS_ARTICLE_CATEGORY_TAG = 'sys_article_category';
    const SYS_ARTICLE_CATEGORY_LIST_KEY = 'sys_article_category_list_%s';


    // 商品
    const GOODS_TAG = 'goods';
    const GOODS_LIST_KEY = 'goods_list_%s';
    const GOODS_PAGES_KEY = 'goods_pages_%s';
    const GOODS_INFO_KEY = 'goods_info_%s';


    // 商品分类
    const GOODS_CATEGORY_TAG = 'goods_category';
    const GOODS_CATEGORY_LIST_KEY = 'goods_category_list_%s';
    const GOODS_CATEGORY_PAGES_KEY = 'goods_category_pages_%s';

    // 商品品牌
    const GOODS_BRAND_TAG = 'goods_brand';
    const GOODS_BRAND_LIST_KEY = 'goods_brand_list_%s';
    const GOODS_BRAND_PAGES_KEY = 'goods_brand_pages_%s';
    const GOODS_BRAND_INFO_KEY = 'goods_brand_info_%s';

    // 商品评论
    const GOODS_COMMENT_TAG = 'goods_comment';
    const GOODS_COMMENT_PAGES_KEY = 'goods_comment_pages_%s';

    // 积分商品评价
    const POINTS_GOODS_EVALUATE_TAG = 'points_goods_evaluate';
    const POINTS_GOODS_EVALUATE_LIST_KEY = 'points_goods_evaluate_list_%s';
    const POINTS_GOODS_EVALUATE_PAGES_KEY = 'points_goods_evaluate_pages_%s';

    // 店铺
    const STORE_TAG = 'store';
    const STORE_INFO_KEY = 'store_info_%s';
    const STORE_LIST_KEY = 'store_list_%s';
    const STORE_PAGES_KEY = 'store_pages_%s';

    // 店铺商品分类
    const STORE_GOODS_CATEGORY_TAG = 'store_goods_category';
    const STORE_GOODS_CATEGORY_LIST_KEY = 'store_goods_category_list_%s';

    // 店铺分类
    const STORE_CATEGORY_TAG = 'store_category';
    const STORE_CATEGORY_LIST_KEY = 'store_category_list_%s';



    // 店铺优惠券
    const STORE_COUPON_TAG = 'store_coupon';
    const STORE_COUPON_LIST_KEY = 'store_coupon_list_%s';
    const STORE_COUPON_PAGES_KEY = 'store_coupon_pages_%s';
    const STORE_COUPON_INFO_KEY = 'store_coupon_info_%s';


    // 师傅
    const TECHNICIAN_TAG = 'technician';
    const TECHNICIAN_LIST_KEY = 'technician_list_%s';
    const TECHNICIAN_PAGES_KEY = 'technician_pages_%s';
    const TECHNICIAN_INFO_KEY = 'technician_info_%s';


    // LBS
    const LBS_CITY_TAG = 'lbs';
    const LBS_CITY_LIST_KEY = 'lbs_city_list';
   
    // 可视化编辑
    const EDITABLE_PAGE_TAG = 'editable_page';
    const EDITABLE_PAGE_INFO_KEY = 'editable_page_info_%s';




    /**
     * 根据配置检查缓存是否启用。
     * 如果默认缓存驱动不是 'null'，则返回 true。
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return env('CACHE_ENABLED', true);
    }

    /**
     * 设置缓存值，支持可选的 TTL。
     *
     * @param string $key 缓存键
     * @param mixed $value 要缓存的值
     * @param int|null $ttl 生存时间（秒），null 表示永久
     * @param string|array|null $tag 缓存标签（可选）
     * @param string|null $store 缓存存储名称（可选）
     * @return bool
     */
    public static function set(string $key, $value, ?int $ttl = null, $tag = null, ?string $store = null): bool
    {
        if (!self::isEnabled()) {
            return false;
        }
        $cache = $store ? Cache::store($store) : Cache::instance();
        if ($tag) {
            $cache = $cache->tag($tag);
        }
        return $cache->set($key, $value, $ttl);
    }

    /**
     * 获取缓存值。
     *
     * @param string $key 缓存键
     * @param mixed $default 如果键不存在的默认值
     * @param string|null $store 缓存存储名称（可选）
     * @return mixed
     */
    public static function get(string $key, $default = null, ?string $store = null)
    {
        if (!self::isEnabled()) {
            return $default;
        }
        $cache = $store ? Cache::store($store) : Cache::instance();
        return $cache->get($key, $default);
    }

    /**
     * 删除缓存条目。
     *
     * @param string $key 缓存键
     * @param string|null $store 缓存存储名称（可选）
     * @return bool
     */
    public static function delete(string $key, ?string $store = null): bool
    {
        if (!self::isEnabled()) {
            return false;
        }
        $cache = $store ? Cache::store($store) : Cache::instance();
        return $cache->delete($key);
    }


    /**
     * 清除所有缓存或指定标签的缓存（谨慎使用）。
     *
     * @param string|array|null $tag 要清除的标签（可选）
     * @param string|null $store 缓存存储名称（可选）
     * @return bool
     */
    public static function clear($tag = null, ?string $store = null): bool
    {
        if (!self::isEnabled()) {
            return false;
        }
        $cache = $store ? Cache::store($store) : Cache::instance();
        if ($tag) {
            return $cache->tag($tag)->clear();
        }
        return $cache->clear();
    }

    /**
     * 递增缓存值（用于计数器）。
     *
     * @param string $key 缓存键
     * @param int $value 递增量（默认 1）
     * @param string|null $store 缓存存储名称（可选）
     * @return int|bool 新值或失败时 false
     */
    public static function increment(string $key, int $value = 1, ?string $store = null)
    {
        if (!self::isEnabled()) {
            return false;
        }
        $cache = $store ? Cache::store($store) : Cache::instance();
        return $cache->inc($key, $value);
    }

    /**
     * 递减缓存值（用于计数器）。
     *
     * @param string $key 缓存键
     * @param int $value 递减量（默认 1）
     * @param string|null $store 缓存存储名称（可选）
     * @return int|bool 新值或失败时 false
     */
    public static function decrement(string $key, int $value = 1, ?string $store = null)
    {
        if (!self::isEnabled()) {
            return false;
        }
        $cache = $store ? Cache::store($store) : Cache::instance();
        return $cache->dec($key, $value);
    }
}
