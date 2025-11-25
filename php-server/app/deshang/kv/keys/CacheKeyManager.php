<?php

namespace app\deshang\kv\keys;

/**
 * 缓存键管理器
 * 
 * 职责：统一管理所有缓存相关的键值常量
 * 包括：缓存键、缓存标签
 */
class CacheKeyManager
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

    //系统文章分类
    const SYS_ARTICLE_CATEGORY_TAG = 'sys_article_category';
    const SYS_ARTICLE_CATEGORY_LIST_KEY = 'sys_article_category_list_%s';


    // 商品
    const GOODS_TAG = 'goods';
    const GOODS_LIST_KEY = 'goods_list_%s';
    const GOODS_PAGES_KEY = 'goods_pages_%s';
    const GOODS_INFO_KEY = 'goods_info_%s';
    const GOODS_RANDOM_KEY = 'goods_random_%s';


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

    // 积分商品
    const POINTS_GOODS_TAG = 'points_goods';
    const POINTS_GOODS_LIST_KEY = 'points_goods_list_%s';
    const POINTS_GOODS_PAGES_KEY = 'points_goods_pages_%s';
    const POINTS_GOODS_INFO_KEY = 'points_goods_info_%s';

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

    // 师傅评论
    const TECHNICIAN_COMMENT_TAG = 'technician_comment';
    const TECHNICIAN_COMMENT_LIST_KEY = 'technician_comment_list_%s';
    const TECHNICIAN_COMMENT_PAGES_KEY = 'technician_comment_pages_%s';


    // LBS
    const LBS_CITY_TAG = 'lbs';
    const LBS_CITY_LIST_KEY = 'lbs_city_list';

    // 可视化编辑
    const EDITABLE_PAGE_TAG = 'editable_page';
    const EDITABLE_PAGE_INFO_KEY = 'editable_page_info_%s';

    // 快递公司
    const SYS_EXPRESS_TAG = 'sys_express';
    const SYS_EXPRESS_LIST_KEY = 'sys_express_list_%s';
}
