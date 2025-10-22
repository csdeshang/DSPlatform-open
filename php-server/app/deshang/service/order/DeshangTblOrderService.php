<?php

namespace app\deshang\service\order;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;


use app\common\enum\order\TblOrderEnum;
use app\common\dao\order\TblOrderDao;
use app\common\dao\order\TblOrderLogDao;
use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderDeliveryDao;
use app\common\dao\order\TblOrderFinanceDao;
use app\common\enum\merchant\MerchantBalanceEnum;
use app\common\enum\order\TblOrderDeliveryEnum;
use app\common\enum\rider\RiderBalanceEnum;
use app\common\enum\technician\TechnicianBalanceEnum;

use app\common\dao\store\TblStoreDao;

use app\deshang\service\merchant\DeshangMerchantBalanceService;
use app\deshang\service\distributor\DeshangDistributorOrderService;
use app\deshang\service\rider\DeshangRiderBalanceService;
use app\deshang\service\technician\DeshangTechnicianBalanceService;
use app\common\enum\trade\TradePaymentConfigEnum;



// 订单服务
class DeshangTblOrderService  extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblOrderDao();
    }


    // 获取店铺可操作的订单状态
    // cancel  店铺 取消订单
    // delivery 店铺 发货
    // refund 店铺 退款
    // confirm 店铺 确认收货
    public function getStoreAvailableActions(array $order_info): array
    {
        $actions = [];

        // 订单已删除，则没有操作权限
        if ($order_info['is_deleted'] == 1) {
            return $actions;
        }


        // 订单状态是取消 没有任何操作
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CANCELLED) {
        }

        // 订单状态是待付款 店铺可以取消
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PENDING) {
            $actions[] = 'close';
            // 修改订单金额
            $actions[] = 'editAmount';
        }

        // 订单状态是已付款 店铺可以发货  取消订单(需要处理退款)
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PAID) {
            $actions[] = 'delivery';
            $actions[] = 'cancel';
        }

        // 订单状态是已发货 ， 店铺可主动发起退款
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_ACCEPTED) {
            $actions[] = 'refund';
        }

        // 订单状态是已确认(针对外卖)
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CONFIRMED) {
        }

        // 订单状态是已完成
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_COMPLETED) {
        }
        return $actions;
    }



    // 获取用户可操作的订单状态
    // delete 用户 删除订单
    // pay 用户 付款
    // cancel 用户 取消订单
    // refund 用户 退款
    // confirm 用户 确认收货
    public function getUserAvailableActions(array $order_info): array
    {
        $actions = [];

        // 订单已删除，则没有操作权限
        if ($order_info['is_deleted'] == 1) {
            return $actions;
        }


        // 订单状态是取消，则可以删除
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CANCELLED) {
            // $actions[] = 'delete';
        }

        // 订单状态是待付款
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PENDING) {
            $actions[] = 'pay';
            // 关闭订单，不再需要进一步处理
            $actions[] = 'close';
        }

        // 订单状态是已付款 , 用户可以取消订单
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PAID) {
            // 取消订单，需要处理退款
            $actions[] = 'cancel';
        }

        // 订单状态是已发货 
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_ACCEPTED) {
            // 没有正在退款的订单，才能确认收货
            if($order_info['refunding_count'] == 0){
                $actions[] = 'confirm';
            }

            $actions[] = 'refund';
        }

        // 订单状态是已确认(针对外卖)
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CONFIRMED) {
            // 没有正在退款的订单，才能确认收货
            if($order_info['refunding_count'] == 0){
                $actions[] = 'confirm';
            }
            $actions[] = 'refund';
        }

        // 订单状态是已完成
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_COMPLETED) {

            // 确认收货后，有一个 allow_refund_time 允许退款时间
            // Model 中 通过获取器  自动转成 DateTime 类型，所以需要转换成时间戳
            $allow_refund_time = strtotime($order_info['allow_refund_time']);
            if ($allow_refund_time > time()) {
                $actions[] = 'refund';
            }

            // $actions[] = 'delete';

            // 评价
            if ($order_info['is_evaluate'] == 0) {
                $actions[] = 'evaluate';
            }
        }
        return $actions;
    }


    public function getSystemAvailableActions(array $order_info): array
    {
        $actions = [];

        // 订单已删除，则没有操作权限
        if ($order_info['is_deleted'] == 1) {
            return $actions;
        }


        // 订单状态是取消，则可以删除
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CANCELLED) {
            // $actions[] = 'delete';
        }

        // 订单状态是待付款
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PENDING) {
            // 关闭订单，不再需要进一步处理
            $actions[] = 'close';
        }

        // 订单状态是已付款 , 系统可以取消订单
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PAID) {
            // 取消订单，需要处理退款
            $actions[] = 'cancel';
        }

        // 订单状态是已发货 
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_ACCEPTED) {
            // 没有正在退款的订单，才能确认收货
            if($order_info['refunding_count'] == 0){
                $actions[] = 'confirm';
            }
        }

        // 订单状态是已确认(针对外卖)
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CONFIRMED) {
            // 没有正在退款的订单，才能确认收货
            if($order_info['refunding_count'] == 0){
                $actions[] = 'confirm';
            }
        }

        // 订单状态是已完成
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_COMPLETED) {
        }
        return $actions;
    }









    // 收到货款，一般有几种情况 
    // 1.用户通过余额支付,支付宝支付，微信支付，正常修改订单状态 
    // 2.如果订单的收款方 pay_merchant_id 是 0, 后台可以手动设置收款 
    // 3.如果收款方是 商户, 则商户账户可设置收到货款。

    // 单个店铺订单 收到货款，如果是合并支付订单，则通过事务循环处理
    public function changeTblOrderReceivePay(array $order_info, string $role, int $user_id = 0, array $extra = array())
    {

        // 用户确认收货后，款项才到商户余额，商户账户没有冻结金额一说。避免资金反复流转，并且订单  pay_merchant_id 是 0 平台收款，才到商户余额。

        // 修改订单状态
        $update = array();
        $update['pay_channel'] = $extra['pay_channel'];
        $update['pay_scene'] = $extra['pay_scene'];

        if (isset($extra['pay_merchant_id'])) {
            $update['pay_merchant_id'] = $extra['pay_merchant_id'];
        }

        if (isset($extra['trade_no'])) {
            $update['trade_no'] = $extra['trade_no'];
        }

        $update['payment_time'] = time();
        $update['order_status'] = TblOrderEnum::ORDER_STATUS_PAID;


        $this->dao->updateOrder(['id' => $order_info['id']], $update);

        $order_log = [
            'order_id' => $order_info['id'],
            'order_status' => $update['order_status'],
            'message' => '支付成功,' . TradePaymentConfigEnum::getPaymentChannelDesc($extra['pay_channel']) . $order_info['pay_amount'] . '元',
            'create_role' => $role,
            'create_by' => $user_id,
            'extra' => json_encode($extra),
        ];

        (new TblOrderLogDao())->createOrderLog($order_log);



        // 修改分销商订单状态（收到货款
        (new DeshangDistributorOrderService())->receivePayDistributorOrder($order_info['id']);


        // 触发支付成功事件
        event('OrderPaySuccessListener', [
            'order_info' => $order_info,
        ]);


        return true;
    }




    // 用户取消订单  已付款，未发货 则可取消订单，涉及到退款 (订单关闭是未付款，订单取消是已付款，未发货)
    public function changeTblOrderCancel(array $order_info, $role, $user_id = '')
    {

        if ($role == 'user') {
            $user_available_actions = $this->getUserAvailableActions($order_info);
            if (!in_array('cancel', $user_available_actions)) {
                throw new CommonException('用户没有取消订单权限');
            }
        } else if ($role == 'store') {
            $store_available_actions = $this->getStoreAvailableActions($order_info);
            if (!in_array('cancel', $store_available_actions)) {
                throw new CommonException('店铺没有取消订单权限');
            }
        } else if ($role == 'system') {
            $system_available_actions = $this->getSystemAvailableActions($order_info);
            if (!in_array('cancel', $system_available_actions)) {
                throw new CommonException('系统没有取消订单权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        

        // 修改分销商订单状态
        (new DeshangDistributorOrderService())->cancelDistributorOrderByOrderId($order_info['id']);


        // 退款处理, 自动处理退款 ， 订单状态 在退款中进行修改
        $refund_id = (new DeshangTblOrderRefundService())->cancelOrderRefund($order_info, $role, $user_id);



        return $refund_id;
    }







    // 关闭订单 (用户未付款，卖家买家都可关闭)
    public function changeTblOrderClose(array $order_info, $role, $user_id = '')
    {

        if ($role == 'user') {
            $user_available_actions = $this->getUserAvailableActions($order_info);
            if (!in_array('close', $user_available_actions)) {
                throw new CommonException('用户没有关闭订单权限');
            }
        } else if ($role == 'store') {
            $store_available_actions = $this->getStoreAvailableActions($order_info);
            if (!in_array('close', $store_available_actions)) {
                throw new CommonException('店铺没有关闭订单权限');
            }
        } else if ($role == 'system') {
            $system_available_actions = $this->getSystemAvailableActions($order_info);
            if (!in_array('close', $system_available_actions)) {
                throw new CommonException('系统没有关闭订单权限');
            }
        } else {
            throw new CommonException('非法操作');
        }





        // 修改订单状态
        $order_update = array();
        $order_update['order_status'] = TblOrderEnum::ORDER_STATUS_CLOSED;
        $order_update['close_time'] = time();

        // 修改订单状态
        $this->dao->updateOrder(['id' => $order_info['id']], $order_update);


        // 修改订单日志
        $order_log = array();
        $order_log['order_id'] = $order_info['id'];
        $order_log['order_status'] = TblOrderEnum::ORDER_STATUS_CLOSED;
        $order_log['message'] = '订单关闭';
        $order_log['create_role'] = $role;
        $order_log['create_by'] = $user_id;

        (new TblOrderLogDao())->createOrderLog($order_log);

        // 修改分销商订单状态
        (new DeshangDistributorOrderService())->cancelDistributorOrderByOrderId($order_info['id']);

        // 触发订单关闭事件
        event('OrderCloseListener', [
            'order_info' => $order_info,
        ]);

        return true;
    }



    // 确认收货
    public function changeTblOrderConfirm(array $order_info, $role, $user_id = '')
    {

        if ($role == 'user') {
            $user_available_actions = $this->getUserAvailableActions($order_info);
            if (!in_array('confirm', $user_available_actions)) {
                throw new CommonException('用户没有确认收货权限');
            }
        } else if ($role == 'store') {
            $store_available_actions = $this->getStoreAvailableActions($order_info);
            if (!in_array('confirm', $store_available_actions)) {
                throw new CommonException('店铺没有确认收货权限');
            }
        } else if ($role == 'system') {
            $system_available_actions = $this->getSystemAvailableActions($order_info);
            if (!in_array('confirm', $system_available_actions)) {
                throw new CommonException('系统没有确认收货权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        // 获取订单交付信息
        $order_delivery_info = (new TblOrderDeliveryDao())->getOrderDeliveryInfo([['order_id', '=', $order_info['id']]]);
        if (empty($order_delivery_info)) {
            throw new CommonException('订单交付信息不存在');
        }
        // 判断订单交付状态(交付信息需要已完成才能确认收货)(PS:也可自动修改成交付完成状态)
        // if ($order_delivery_info['delivery_status'] != TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED) {
        //     throw new CommonException('订单未完成交付不能确认收货');
        // }


        // 修改订单状态
        $order_update = array();
        $order_update['order_status'] = TblOrderEnum::ORDER_STATUS_COMPLETED;
        $order_update['finnshed_time'] = time();
        // 设置允许退款时间
        $allow_refund_time = sysConfig('order_auto:refund_order_enabled') == 1 ? sysConfig('order_auto:refund_order_days') * 24 * 3600 : 0;
        $order_update['allow_refund_time'] = time() + $allow_refund_time;



        // 修改订单状态
        $this->dao->updateOrder(['id' => $order_info['id']], $order_update);


        // 添加订单日志
        $order_log = array();
        $order_log['order_id'] = $order_info['id'];
        $order_log['order_status'] = TblOrderEnum::ORDER_STATUS_COMPLETED;
        $order_log['message'] = '确认收货,' . '订单ID：' . $order_info['id'];
        $order_log['create_role'] = $role;
        $order_log['create_by'] = $user_id;
        (new TblOrderLogDao())->createOrderLog($order_log);


        //获取店铺信息
        $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $order_info['store_id']]], '*');
        if (empty($store_info)) {
            throw new CommonException('店铺不存在');
        }

        // 如果订单的收款方 是 0(平台收款) ，则将款项转到商户余额
        if ($order_info['pay_merchant_id'] == 0) {

            // 增加商户余额
            $balance_data = [
                'change_mode' => MerchantBalanceEnum::MODE_INCREASE,
                'change_type' => MerchantBalanceEnum::TYPE_ORDER,
                'change_amount' => $order_info['pay_amount'],
                'merchant_id' => $store_info['merchant_id'],
                'store_id' => $order_info['store_id'],
                'related_id' => $order_info['id'],
                'change_desc' => '确认收货,' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangMerchantBalanceService())->modifyMerchantBalance($balance_data);
        }


        // 扣除店铺的 service_amount 服务费,用户退款不退回 (不论商户收款还是店铺收款均需扣除)
        if ($order_info['service_amount'] > 0) {
            $balance_data = [
                'change_mode' => MerchantBalanceEnum::MODE_DECREASE,
                'change_type' => MerchantBalanceEnum::TYPE_SERVICE_FEE,
                'change_amount' => $order_info['service_amount'],
                'merchant_id' => $store_info['merchant_id'],
                'store_id' => $order_info['store_id'],
                'related_id' => $order_info['id'],
                'change_desc' => '扣除平台服务费' . $order_info['service_amount'] . '元' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangMerchantBalanceService())->modifyMerchantBalance($balance_data);
        }



        // 用户确认收货，先扣除掉商户店铺的佣金，但是可能涉及到退款，分销商订单可能需要修改为失效状态，然后把分销商佣金退回给店铺   所以与分销商佣金结算  需要等订单不允许退款后，再进行结算
        $total_commission_amount = (new DeshangDistributorOrderService())->getDistributorOrderCommissionAmount($order_info);
        // 扣除商户佣金
        if ($total_commission_amount > 0) {
            $balance_data = [
                'change_mode' => MerchantBalanceEnum::MODE_DECREASE,
                'change_type' => MerchantBalanceEnum::TYPE_COMMISSION,
                'change_amount' => $total_commission_amount,
                'merchant_id' => $store_info['merchant_id'],
                'store_id' => $order_info['store_id'],
                'related_id' => $order_info['id'],
                'change_desc' => '扣除分销佣金' . $total_commission_amount . '元' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangMerchantBalanceService())->modifyMerchantBalance($balance_data);
        }


        // 如果是骑手配送订单，则需要扣除商户的配送费，增加骑手的配送费(因为后期可能涉及到店铺自行收款，所以需要单独处理)
        if ($order_info['delivery_method'] == TblOrderEnum::DELIVERY_RIDER) {

            // 扣除商户的配送费
            $balance_data = [
                'change_mode' => MerchantBalanceEnum::MODE_DECREASE,
                'change_type' => MerchantBalanceEnum::TYPE_RIDER_FEE,
                'change_amount' => $order_delivery_info['rider_total_fee'],
                'merchant_id' => $store_info['merchant_id'],
                'store_id' => $order_info['store_id'],
                'related_id' => $order_info['id'],
                'change_desc' => '扣除骑手配送费' . $order_delivery_info['rider_total_fee'] . '元' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangMerchantBalanceService())->modifyMerchantBalance($balance_data);


            // 增加骑手的配送费
            $balance_data = [
                'rider_id' => $order_delivery_info['rider_id'],
                'related_id' => $order_info['id'],
                'change_type' => RiderBalanceEnum::TYPE_DELIVERY_FEE,
                'change_mode' => RiderBalanceEnum::MODE_INCREASE,
                'change_amount' => $order_delivery_info['rider_fee'],
                'change_desc' => '确认收货，配送费' . $order_delivery_info['rider_fee'] . '元,用户实际支付配送费' . $order_delivery_info['rider_total_fee'] . '元' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangRiderBalanceService())->modifyRiderBalance($balance_data);
        }


        // 如果是师傅服务订单，则需要扣除师傅的服务费，增加师傅的实际收入(因为后期可能涉及到店铺自行收款，所以需要单独处理)
        if ($order_info['delivery_method'] == TblOrderEnum::DELIVERY_TECHNICIAN) {

            // 师傅服务费 = 师傅服务费 + 路程费
            $technician_fee = $order_delivery_info['technician_fee'] + $order_info['shipping_amount'];

            // 扣除商户的配送费
            $balance_data = [
                'change_mode' => MerchantBalanceEnum::MODE_DECREASE,
                'change_type' => MerchantBalanceEnum::TYPE_TECHNICIAN_FEE,
                'change_amount' => $technician_fee,
                'merchant_id' => $store_info['merchant_id'],
                'store_id' => $order_info['store_id'],
                'related_id' => $order_info['id'],
                'change_desc' => '扣除师傅服务费' . $technician_fee . '元' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangMerchantBalanceService())->modifyMerchantBalance($balance_data);

            // 增加骑手的配送费
            $balance_data = [
                'technician_id' => $order_delivery_info['technician_id'],
                'related_id' => $order_info['id'],
                'change_type' => TechnicianBalanceEnum::TYPE_TECHNICIAN_FEE,
                'change_mode' => TechnicianBalanceEnum::MODE_INCREASE,
                'change_amount' => $technician_fee,
                'change_desc' => '确认收货，师傅服务费' . $technician_fee . '元,其中路程费' . $order_info['shipping_amount'] . '元' . '(订单ID：' . $order_info['id'] . ')',
            ];
            (new DeshangTechnicianBalanceService())->modifyTechnicianBalance($balance_data);

        }




        // 创建订单资金分配表/订单分账
        $order_finance = array(
            'order_id' => $order_info['id'],
            'pay_amount' => $order_info['pay_amount'],
            'distributor_amount' => $total_commission_amount,
            // 店铺给平台的佣金(服务费)
            'store_sys_amount' => $order_info['service_amount'],
            // 骑手配送费
            'rider_amount' => $order_delivery_info['rider_fee'],
            // 骑手给平台的佣金
            'rider_sys_amount' => $order_delivery_info['rider_total_fee'] - $order_delivery_info['rider_fee'],
            // 师傅服务费
            'technician_amount' => $order_delivery_info['technician_fee'] + $order_info['shipping_amount'],
            'technician_service_fee' => $order_delivery_info['technician_fee'],
            'technician_trip_fee' => $order_info['shipping_amount'],
            // 结算状态
            'finance_status' => 1,
            'settle_time' => time(),
        );
        $order_finance['store_amount'] = $order_info['pay_amount'] - $total_commission_amount - $order_info['service_amount'] - $order_delivery_info['rider_total_fee'] - $order_delivery_info['technician_fee'] - $order_info['shipping_amount'];
        (new TblOrderFinanceDao())->createOrderFinance($order_finance);

        // 使用事件或消息队列(待完善)

        return true;
    }



    /**
     * 修改订单金额  只更新 order 中 pay_amount shipping_amount order_goods 中 pay_price
     * 
     * @param array $order_info 订单信息
     * @param string $role 操作角色
     * @param int $user_id 操作用户ID
     * @param array $extra 额外数据，包含goods_list、shipping_amount、modify_reason等
     * @return bool 操作结果
     * @throws CommonException
     */
    public function changeTblOrderAmount(array $order_info, string $role, int $user_id = 0, array $extra = array())
    {

        if ($role == 'store') {
            $store_available_actions = $this->getStoreAvailableActions($order_info);
            if (!in_array('editAmount', $store_available_actions)) {
                throw new CommonException('店铺没有修改订单金额权限');
            }
        } else {
            throw new CommonException('非法操作');
        }


        // 判断订单是否是未支付状态
        if ($order_info['order_status'] !== TblOrderEnum::ORDER_STATUS_PENDING) {
            throw new CommonException('订单状态不是未付款，不能修改订单金额');
        }

        // 验证参数
        if (empty($extra['goods_list'])) {
            throw new CommonException('商品列表不能为空');
        }

        // 计算新的商品总金额
        $total_pay_price = 0;
        $orderGoodsDao = new TblOrderGoodsDao();
        $order_goods_ids = array_column($extra['goods_list'], 'id');


        // 获取订单商品信息
        $order_goods_list = $orderGoodsDao->getOrderGoodsList([
            ['id', 'in', $order_goods_ids],
            ['order_id', '=', $order_info['id']]
        ]);

        if (count($order_goods_list) !== count($extra['goods_list'])) {
            throw new CommonException('部分商品不属于该订单');
        }




        // 更新订单商品价格
        foreach ($extra['goods_list'] as $order_goods) {
            $pay_price = round($order_goods['pay_price'], 2);

            // 查找对应的订单商品
            $temp_order_goods = null;
            foreach ($order_goods_list as $og) {
                if ($og['id'] == $order_goods['id']) {
                    $temp_order_goods = $og;
                    break;
                }
            }

            if (!$temp_order_goods) {
                throw new CommonException('商品不存在');
            }

            // 更新商品价格
            $orderGoodsDao->updateOrderGoods(['id' => $order_goods['id']], [
                'pay_price' => $pay_price
            ]);

            // 累加商品总金额
            $total_pay_price += $pay_price * $temp_order_goods['goods_num'];
        }

        // 设置运费
        $shipping_amount = isset($extra['shipping_amount']) ? round($extra['shipping_amount'], 2) : $order_info['shipping_amount'];

        // 更新订单支付金额   支付金额 = 订单商品(pay_price) * 数量 + 运费  其他金额不变 只更新  pay_amount
        $pay_amount = $total_pay_price + $shipping_amount;

        // 更新订单金额
        $this->dao->updateOrder(['id' => $order_info['id']], [
            'shipping_amount' => $shipping_amount,
            'pay_amount' => $pay_amount,
        ]);

        // 记录订单日志
        $log_data = [
            'order_id' => $order_info['id'],
            'order_status' => $order_info['order_status'],
            'message' => '修改订单金额，原金额：' . round($order_info['pay_amount'], 2) . '，新金额：' . $pay_amount,
            'create_role' => $role,
            'create_by' => $user_id,
            'extra' => json_encode($extra)
        ];

        (new TblOrderLogDao())->createOrderLog($log_data);

        return true;
    }
}
