<?php

namespace app\deshang\utils;

/**
 * 搜索辅助工具类
 * 
 * 提供通用的搜索功能，如模糊查询转ID列表等
 */
class SearchHelper
{
    /**
     * 根据店铺名称模糊查询并返回ID列表
     * 
     * @param string $storeName 店铺名称
     * @return array ID列表
     */
    public static function getStoreIdsByStoreName(string $storeName): array
    {
        if (empty($storeName)) {
            return [];
        }
        
        $storeDao = new \app\common\dao\store\TblStoreDao();
        return $storeDao->getStoreColumn([
            ['store_name', 'like', '%' . $storeName . '%']
        ], 'id');
    }

    /**
     * 根据用户名称模糊查询并返回ID列表
     * 
     * @param string $username 用户名称
     * @return array ID列表
     */
    public static function getUserIdsByUsername(string $username): array
    {
        if (empty($username)) {
            return [];
        }
        $userDao = new \app\common\dao\user\UserDao();
        return $userDao->getUserColumn([
            ['username', 'like', '%' . $username . '%']
        ], 'id');
    }

    /**
     * 根据商户名称模糊查询并返回ID列表
     * 
     * @param string $merchantName 商户名称
     * @return array ID列表
     */
    public static function getMerchantIdsByMerchantName(string $merchantName): array
    {
        if (empty($merchantName)) {
            return [];
        }
        
        $merchantDao = new \app\common\dao\merchant\MerchantDao();
        return $merchantDao->getMerchantColumn([
            ['name', 'like', '%' . $merchantName . '%']
        ], 'id');
    }

    /**
     * 根据商户名称模糊查询并返回店铺ID列表
     * 
     * @param string $merchantName 商户名称
     * @return array 店铺ID列表
     */
    public static function getStoreIdsByMerchantName(string $merchantName): array
    {
        if (empty($merchantName)) {
            return [];
        }
        
        // 先根据商户名获取商户ID列表
        $merchantIds = self::getMerchantIdsByMerchantName($merchantName);
        
        if (empty($merchantIds)) {
            return [];
        }
        
        // 再根据商户ID列表获取店铺ID列表
        $storeDao = new \app\common\dao\store\TblStoreDao();
        return $storeDao->getStoreColumn([
            ['merchant_id', 'in', $merchantIds]
        ], 'id');
    }

    /**
     * 根据商品名称模糊查询并返回ID列表
     * 
     * @param string $goodsName 商品名称
     * @return array ID列表
     */
    public static function getGoodsIdsByGoodsName(string $goodsName): array
    {
        if (empty($goodsName)) {
            return [];
        }
        
        $goodsDao = new \app\common\dao\goods\TblGoodsDao();
        return $goodsDao->getGoodsColumn([
            ['goods_name', 'like', '%' . $goodsName . '%']
        ], 'id');
    }

    /**
     * 根据订单编号模糊查询并返回订单ID列表
     * 
     * @param string $orderSn 订单编号
     * @return array 订单ID列表
     */
    public static function getOrderIdsByOrderSn(string $orderSn): array
    {
        if (empty($orderSn)) {
            return [];
        }
        
        $orderDao = new \app\common\dao\order\TblOrderDao();
        return $orderDao->getOrderColumn([
            ['order_sn', 'like', '%' . $orderSn . '%']
        ], 'id');
    }

    

}