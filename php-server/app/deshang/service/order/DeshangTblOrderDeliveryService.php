<?php

namespace app\deshang\service\order;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\StateException;



use app\common\dao\order\TblOrderDao;
use app\common\dao\order\TblOrderDeliveryDao;
use app\common\dao\order\TblOrderLogDao;

use app\common\enum\order\TblOrderEnum;
use app\common\enum\order\TblOrderDeliveryEnum;

// 订单交付
class DeshangTblOrderDeliveryService  extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }

    // 店铺创建订单交付
    public function addTblOrderDelivery(array $order_info, array $data, string $create_role = 'user', int $user_id = 0)
    {

        if ($order_info['order_status'] != TblOrderEnum::ORDER_STATUS_PAID) {
            throw new StateException('订单状态错误,不是已付款状态');
        }

        // 判断是否存在订单交付
        $order_delivery = (new TblOrderDeliveryDao())->getOrderDeliveryInfo([['order_id', '=', $order_info['id']]]);

        $delivery_method = $data['delivery_method'];

        // 如果已经确定了交付方式，则不进行更新
        if (!empty($order_delivery)) {
            if ($order_delivery['delivery_method'] != $delivery_method) {
                throw new CommonException('订单交付方式错误,不能修改交付方式');
            }
        }


        switch ($delivery_method) {
            case TblOrderEnum::DELIVERY_VIRTUAL:
                // 虚拟交付
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    // 虚拟交付 默认完成
                    'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED,
                );
                break;
            case TblOrderEnum::DELIVERY_EXPRESS:
                // 快递交付
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    'express_type' => $data['express_type'],
                    'express_number' => $data['express_number'],
                    'express_company' => $data['express_company'],
                    // 快递交付 默认完成
                    'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED,
                );
                break;
            case TblOrderEnum::DELIVERY_IN_STORE:
                // 到店自提
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    // 到店自提 默认完成
                    'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED,
                );
                break;
            case TblOrderEnum::DELIVERY_RIDER:
                // 骑手配送
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    // 骑手配送 默认待分配
                    'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_PENDING_ALLOCATION,
                );
                break;
            case TblOrderEnum::DELIVERY_DINE_IN:
                // 堂食
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    // 堂食 默认完成
                    'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED,
                );
                break;
            case TblOrderEnum::DELIVERY_ONSITE:
                // 现场服务
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    // 现场服务 默认完成
                    'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED,
                );
                break;
            case TblOrderEnum::DELIVERY_ERRANDS:
                // 跑腿服务
                throw new CommonException('暂时不支持跑腿服务');
                break;
            case TblOrderEnum::DELIVERY_TECHNICIAN:
                // 上门服务
                $order_delivery_data = array(
                    'order_id' => $order_info['id'],
                    // 处理订单发货， 上门服务的交付订单状态 不需修改
                    // 'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_PENDING_ALLOCATION,
                );

                // 获取师傅信息

                // 给师傅 排班 


                break;
            case TblOrderEnum::DELIVERY_ONSITE:
                // 现场服务 (酒店类，景点类)
                throw new CommonException('暂时不支持现场服务');
                break;
            default:
                throw new CommonException('不支持的交付方式');
        }

        // 设置交付方式 (订单表和交付表同时更新)
        $order_delivery_data['delivery_method'] = $delivery_method;
        if (!empty($order_delivery)) {
            // 已存在则更新
            (new TblOrderDeliveryDao())->updateOrderDelivery([['order_id', '=', $order_info['id']]], $order_delivery_data);
        } else {
            // 不存在则创建
            (new TblOrderDeliveryDao())->createOrderDelivery($order_delivery_data);
        }


        // 更新订单状态
        $order_data = array(
            'order_status' => TblOrderEnum::ORDER_STATUS_ACCEPTED,
            'delivery_method' => $delivery_method,
            'delivery_time' => time(),
        );
        (new TblOrderDao())->updateOrder([['id', '=', $order_info['id']]], $order_data);


        // 如交付方式不是 师傅服务 则新增订单日志
        if (!in_array($delivery_method, [TblOrderEnum::DELIVERY_TECHNICIAN])) {
            // 新增订单日志
            $order_log_data = array(
                'order_id' => $order_info['id'],
                'order_status' => TblOrderEnum::ORDER_STATUS_ACCEPTED,
                'message' => '卖家已发货',
                'create_role' => $create_role,
                'create_by' => $user_id,
                // extra 参数
                'extra' => json_encode($data),
            );
            (new TblOrderLogDao())->createOrderLog($order_log_data);
        }



        return true;
    }
}
