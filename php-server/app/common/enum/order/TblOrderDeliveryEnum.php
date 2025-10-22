<?php

namespace app\common\enum\order;


/**
 * 订单配送相关枚举
 */
class TblOrderDeliveryEnum
{

    const DELIVERY_STATUS_CANCELLED = 10; // 已取消
    const DELIVERY_STATUS_PENDING_STORE_CONFIRMATION = 20; // 待店铺确认，发货后修改未待分配或发货完成(订单默认插入的信息，例如外卖的配送费用等信息)
    const DELIVERY_STATUS_PENDING_ALLOCATION = 30; // 待分配 快递单号,骑手,服务人员 
    const DELIVERY_STATUS_COURIER_ACCEPTANCE = 40; // 待服务人员确认
    const DELIVERY_STATUS_COURIER_DEPARTURE = 50; // 待服务人员出发
    const DELIVERY_STATUS_ARRIVING = 60; // 到达中(前往中) 快递取件中,骑手到店中,服务人员上门中
    const DELIVERY_STATUS_ARRIVED = 70;  // 已到达 快递到店完成,骑手到店完成,服务人员上门完成(物流中,配送中,服务中)
    const DELIVERY_STATUS_PROCESSING = 80;  // 进行中(配送中/服务中)
    // 简化操作流程  (物流中,配送中,服务中)  如需单独流程可单独加上
    const DELIVERY_STATUS_COMPLETED = 90;  // 发货完成,配送完成,服务完成
    // const DELIVERY_STATUS_RECEIVED = 100;  // 确认收货  用户已经确认收货 (预留状态)

    // 交付方式与状态流程映射
    private static $deliveryTypeFlows = [

        // 所有交付方式
        'all' => [
            self::DELIVERY_STATUS_CANCELLED => '已取消',
            self::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION => '待确认',
            self::DELIVERY_STATUS_PENDING_ALLOCATION => '待分配',
            self::DELIVERY_STATUS_COURIER_ACCEPTANCE => '待接单',
            self::DELIVERY_STATUS_COURIER_DEPARTURE => '待出发',
            self::DELIVERY_STATUS_ARRIVING => '在途中',
            self::DELIVERY_STATUS_ARRIVED => '已到达',
            self::DELIVERY_STATUS_PROCESSING => '进行中',
            self::DELIVERY_STATUS_COMPLETED => '已完成',
        ],


        // 虚拟交付
        TblOrderEnum::DELIVERY_VIRTUAL => [
            self::DELIVERY_STATUS_CANCELLED => '已取消',
            self::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION => '待确认',
            self::DELIVERY_STATUS_COMPLETED => '已完成',
        ],
        // 快递交付
        TblOrderEnum::DELIVERY_EXPRESS => [
            self::DELIVERY_STATUS_CANCELLED => '已取消',
            self::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION => '待确认',
            self::DELIVERY_STATUS_COMPLETED => '已发货',
        ],
        // 到店自提
        TblOrderEnum::DELIVERY_IN_STORE => [
            self::DELIVERY_STATUS_CANCELLED => '已取消',
            self::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION => '待确认',
            self::DELIVERY_STATUS_COMPLETED => '待自提',
        ],
        // 骑手配送
        TblOrderEnum::DELIVERY_RIDER => [
            self::DELIVERY_STATUS_CANCELLED => '已取消',
            self::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION => '待确认',
            self::DELIVERY_STATUS_PENDING_ALLOCATION => '待分配骑手',
            self::DELIVERY_STATUS_ARRIVING => '待取货',
            self::DELIVERY_STATUS_ARRIVED => '已到店',
            self::DELIVERY_STATUS_PROCESSING => '配送中',
            self::DELIVERY_STATUS_COMPLETED => '已送达',
        ],
        // 上门服务
        TblOrderEnum::DELIVERY_TECHNICIAN => [
            self::DELIVERY_STATUS_CANCELLED => '已取消',
            self::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION => '待确认',
            self::DELIVERY_STATUS_PENDING_ALLOCATION => '待分配', // 待分配师傅
            self::DELIVERY_STATUS_COURIER_ACCEPTANCE => '待确认', // 待师傅确认
            self::DELIVERY_STATUS_COURIER_DEPARTURE => '待出发', // 待师傅出发
            self::DELIVERY_STATUS_ARRIVING => '路途中', // 待上门/路途中
            self::DELIVERY_STATUS_ARRIVED => '已到达', // 已到达
            self::DELIVERY_STATUS_PROCESSING => '服务中', // 服务中
            self::DELIVERY_STATUS_COMPLETED => '已完成',
        ],
        // 其他交付方式...
    ];


    /**
     * 获取特定交付方式的状态字典
     * 
     * @param string $deliveryType 交付方式
     * @return array 状态字典
     */
    public static function getDeliveryStatusDict($deliveryType): array
    {
        return self::$deliveryTypeFlows[$deliveryType] ?? self::$deliveryTypeFlows['all'];
    }

    /**
     * 获取特定交付方式的状态描述
     * 
     * @param string $deliveryType 交付方式
     * @param int $status 状态码
     * @return string 状态描述
     */
    public static function getDeliveryStatusDesc($deliveryType, int $status): string
    {
        $dict = self::getDeliveryStatusDict($deliveryType);
        return $dict[$status] ?? '未知状态';
    }
}
