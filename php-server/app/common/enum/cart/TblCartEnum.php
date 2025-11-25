<?php

namespace app\common\enum\cart;

/**
 * 购物车枚举类
 */
class TblCartEnum
{
    /**
     * 购物车商品状态
     */
    const CART_STATUS_NORMAL = 1;           // 正常
    const CART_STATUS_OUT_OF_STOCK = 2;      // 库存不足
    const CART_STATUS_DISCONTINUED = 3;      // 商品下架
    const CART_STATUS_UNAUDITED = 4;         // 商品未审核
    const CART_STATUS_DELETED = 5;           // 商品删除

    /**
     * 获取购物车状态描述
     * @param int $status 状态值
     * @return string 状态描述
     */
    public static function getCartStatusDesc($status)
    {
        $statusMap = [
            self::CART_STATUS_NORMAL => '正常',
            self::CART_STATUS_OUT_OF_STOCK => '库存不足',
            self::CART_STATUS_DISCONTINUED => '商品下架',
            self::CART_STATUS_UNAUDITED => '商品未审核',
            self::CART_STATUS_DELETED => '商品删除',
        ];

        return $statusMap[$status] ?? '未知状态';
    }

    /**
     * 获取购物车状态消息
     * @param int $status 状态值
     * @param array $params 额外参数
     * @return string 状态消息
     */
    public static function getCartStatusMessage($status, $params = [])
    {
        switch ($status) {
            case self::CART_STATUS_NORMAL:
                return '';
                
            case self::CART_STATUS_OUT_OF_STOCK:
                $stock = $params['stock'] ?? 0;
                return "库存不足，当前库存：{$stock}";
                
            case self::CART_STATUS_DISCONTINUED:
                return '商品已下架，无法购买';
                
            case self::CART_STATUS_UNAUDITED:
                return '商品未通过审核';
                
            case self::CART_STATUS_DELETED:
                return '商品已被删除';
                
            default:
                return '商品状态异常';
        }
    }

}
