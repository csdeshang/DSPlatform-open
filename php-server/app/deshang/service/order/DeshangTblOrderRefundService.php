<?php

namespace app\deshang\service\order;


use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\PermissionException;
use app\deshang\exceptions\StateException;

use app\deshang\service\BaseDeshangService;

use app\common\dao\order\TblOrderDao;
use app\common\dao\order\TblOrderLogDao;
use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderRefundDao;
use app\common\dao\order\TblOrderRefundLogDao;
use app\common\dao\order\TblOrderFinanceDao;
use app\common\dao\store\TblStoreDao;
use app\common\dao\trade\TradeRefundLogDao;
use app\common\dao\order\TblOrderDeliveryDao;

use app\common\enum\merchant\MerchantBalanceEnum;
use app\common\enum\user\UserBalanceEnum;
use app\common\enum\trade\TradeRefundEnum;
use app\common\enum\trade\TradePaymentConfigEnum;
use app\common\enum\order\TblOrderRefundEnum;
use app\common\enum\order\TblOrderEnum;
use app\common\enum\order\TblOrderDeliveryEnum;

use app\deshang\service\merchant\DeshangMerchantBalanceService;

use app\deshang\service\user\DeshangUserBalanceService;
use app\deshang\service\order\DeshangTblOrderService;

use app\deshang\core\ThirdPartyLoader;

use app\deshang\service\distributor\DeshangDistributorOrderService;

use think\facade\Db;



/**
 * 订单退款服务类
 */
class DeshangTblOrderRefundService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getStoreRefundActions(array $refund_info): array
    {

        $actions = [];

        // 店铺发起退款
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_APPLYING) {
            $actions[] = 'agree_refund';
            $actions[] = 'reject_refund';
        }

        // 用户申请中
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_USER_APPLYING) {
            $actions[] = 'agree_refund';
            $actions[] = 'reject_refund';
        }

        // 店铺同意 ， 如果是仅退款  则 状态直接进入退款中  STATUS_REFUND_PROCESSING ，退货则等待买家发货
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_AGREED) {
        }

        // 店铺拒绝
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_REJECTED) {
            // $actions[] = 'agree_refund';
        }

        // 用户退货
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_USER_RETURNED) {
            // 退货退款 店铺才有收货权限
            if ($refund_info['refund_type'] == TblOrderRefundEnum::TYPE_REFUND_GOODS) {
                $actions[] = 'receive_goods';
            }
        }

        // 店铺已收货
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_RECEIVED) {
        }
        // 退款处理中
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_REFUND_PROCESSING) {
            $actions[] = 'retry_refund';
        }
        // 退款成功
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_REFUND_SUCCESS) {
        }
        // 退款失败  可重新发起退款
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_REFUND_FAILED) {
            $actions[] = 'retry_refund';
        }
        return $actions;
    }


    // 用户退款 可操作
    public function getUserRefundActions(array $refund_info): array
    {
        $actions = [];

        // 店铺发起退款
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_APPLYING) {
            $actions[] = 'edit_refund';
        }

        // 用户申请中
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_USER_APPLYING) {
            $actions[] = 'edit_refund';
            $actions[] = 'cancel_refund';
        }

        // 店铺同意 ， 如果是仅退款  则 状态直接进入退款中  STATUS_REFUND_PROCESSING 否则需要等买家发货
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_AGREED) {
            if ($refund_info['refund_type'] == TblOrderRefundEnum::TYPE_REFUND_GOODS) {
                $actions[] = 'return_goods';
            }
        }

        // 店铺拒绝
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_REJECTED) {
            $actions[] = 'edit_refund';
        }
        // 用户退货
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_USER_RETURNED) {
        }

        // 店铺已收货
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_STORE_RECEIVED) {
        }
        // 退款处理中
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_REFUND_PROCESSING) {
        }
        // 退款成功
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_REFUND_SUCCESS) {
        }
        // 退款失败  可重新发起退款
        if ($refund_info['refund_status'] == TblOrderRefundEnum::STATUS_REFUND_FAILED) {
        }

        return $actions;
    }



    // 取消订单退款， 无需店铺操作，自动退款
    public function cancelOrderRefund(array $order_info, string $role, int $user_id): int
    {
        // 订单状态 已付款 未发货 才能取消订单退款
        if ($order_info['order_status'] != TblOrderEnum::ORDER_STATUS_PAID) {
            throw new StateException('订单状态错误');
        }


        // 新增退款订单
        $refundData = [
            'platform' => $order_info['platform'],
            'order_id' => $order_info['id'],
            'order_goods_id' => 0,
            'out_refund_no' => generateOutTradeNo('refund', $order_info['user_id']),
            'user_id' => $order_info['user_id'],
            'store_id' => $order_info['store_id'],
            'apply_amount' => $order_info['pay_amount'],
            'refund_merchant_id' => $order_info['pay_merchant_id'],
            'refund_type' => TblOrderRefundEnum::TYPE_ONLY_REFUND,
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_PROCESSING,
            'refund_amount' => $order_info['pay_amount'],
            'refund_explain' => '退款(取消订单)' . '(订单ID：' . $order_info['id'] . ')',
            'refund_address' => '',
            'express_number' => '',
            'express_company' => '',
        ];

        $refundId = (new TblOrderRefundDao())->createOrderRefund($refundData);


        // 记录退款日志
        $logData = [
            'refund_id' => $refundId,
            'refund_status' => TblOrderRefundEnum::STATUS_USER_APPLYING,
            'message' => '退款(取消订单)' . '(订单ID：' . $order_info['id'] . ')',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];

        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);

        // 申请退款，增加正在退款的订单数量
        (new TblOrderDao())->setOrderInc(['id' => $order_info['id']], 'refunding_count');


        // 已付款 未发货订单，修改订单状态,修改为取消订单  (退款流程 通过单独事务处理)
        $update = [
            'order_status' => TblOrderEnum::ORDER_STATUS_CANCELLED,
            'cancel_time' => time(),
        ];
        (new TblOrderDao())->updateOrder(['id' => $order_info['id']], $update);

        // 修改订单交付状态
        $update = [
            'delivery_status' => TblOrderDeliveryEnum::DELIVERY_STATUS_CANCELLED,
        ];
        (new TblOrderDeliveryDao())->updateOrderDelivery(['order_id' => $order_info['id']], $update);


        // 新增订单日志
        $order_log = [
            'order_id' => $order_info['id'],
            'order_status' => TblOrderEnum::ORDER_STATUS_CANCELLED,
            'message' => '用户取消订单',
            'create_role' => $role,
            'create_by' => $user_id,
        ];
        (new TblOrderLogDao())->createOrderLog($order_log);







        return $refundId;
    }





    /**
     * 发起退款(店铺，用户 都可以发起)
     * 
     * @param array $data 退款数据
     * @return int 退款ID
     */
    public function applyRefund(array $order_info, array $data, string $role, int $user_id): bool
    {
        // 验证订单状态是否允许退款
        if ($role == 'user') {
            $user_available_actions = (new DeshangTblOrderService())->getUserAvailableActions($order_info);

            if (!in_array('refund', $user_available_actions)) {
                throw new PermissionException('用户没有退款权限');
            }
        } else if ($role == 'store') {
            $store_available_actions = (new DeshangTblOrderService())->getStoreAvailableActions($order_info);
            if (!in_array('refund', $store_available_actions)) {
                throw new PermissionException('店铺没有退款权限');
            }
        } else {
            throw new CommonException('非法操作');
        }



        // 使用枚举类验证退款类型
        if (!array_key_exists($data['refund_type'], TblOrderRefundEnum::getAllRefundTypeDict())) {
            throw new CommonException('退款类型错误');
        }


        // 全额退款是否存在
        $full_refund = (new TblOrderRefundDao())->getOrderRefundInfo(['order_id' => $order_info['id'], 'order_goods_id' => 0]);
        if ($full_refund) {
            throw new CommonException('当前订单已存在全额退款，不能重复申请');
        }

        // 当前订单下 所有退款金额 是否大于 订单金额
        $total_refund_amount = (new TblOrderRefundDao())->getOrderRefundSum(['order_id' => $order_info['id']], 'refund_amount');
        if ($total_refund_amount + $data['apply_amount'] > $order_info['pay_amount']) {
            throw new CommonException('当前订单下 所有退款金额 已大于 订单金额');
        }


        // 订单商品表主键ID 为 0 时，表示全额退款
        $order_goods_id = $data['order_goods_id'] ?? 0;

        if ($order_goods_id == 0) {
            // 全额退款 ，存在退款，不允许申请全额退款
            $refund = (new TblOrderRefundDao())->getOrderRefundInfo(['order_id' => $order_info['id']]);
            if ($refund) {
                throw new CommonException('当前订单已存在退款，不能重复申请全额退款');
            }
        } else {
            // 部分退款

            // 订单商品表主键ID 是否存在
            $order_goods_info = (new TblOrderGoodsDao())->getOrderGoodsInfoById($order_goods_id);
            if (!$order_goods_info) {
                throw new CommonException('当前订单商品不存在');
            }

            // 订单商品表主键ID 是否存在退款
            $refund = (new TblOrderRefundDao())->getOrderRefundInfo(['order_goods_id' => $order_goods_id]);
            if ($refund) {
                throw new CommonException('当前订单商品已存在退款，不能重复申请');
            }

            // 申请的退款金额是否大于订单商品 实际支付金额
            if ($data['apply_amount'] > $order_goods_info['pay_price'] * $order_goods_info['goods_num']) {
                throw new CommonException('申请的退款金额大于订单商品实际支付金额');
            }
        }


        // 未发货的订单,不允许退货退款
        if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_PAID && $data['refund_type'] == TblOrderRefundEnum::TYPE_REFUND_GOODS) {
            throw new CommonException('未发货的订单,不允许退货退款');
        }



        $refundData = [
            'platform' => $order_info['platform'],
            'order_id' => $order_info['id'],
            'order_goods_id' => $order_goods_id,
            'out_refund_no' => generateOutTradeNo('refund', $order_info['user_id']),
            'user_id' => $order_info['user_id'],
            'store_id' => $order_info['store_id'],
            'apply_amount' => $data['apply_amount'],
            'refund_merchant_id' => $order_info['pay_merchant_id'],
            'refund_type' => $data['refund_type'],
            'refund_status' => $role == 'store' ? TblOrderRefundEnum::STATUS_STORE_APPLYING : TblOrderRefundEnum::STATUS_USER_APPLYING,
            'refund_amount' => $data['apply_amount'],
            'refund_explain' => $data['refund_explain'],
            'refund_images' => $data['refund_images'] ?? '',
            'refund_address' => '',
            'express_number' => '',
            'express_company' => '',
        ];

        $refundId = (new TblOrderRefundDao())->createOrderRefund($refundData);

        // 申请退款，增加正在退款的订单数量
        (new TblOrderDao())->setOrderInc(['id' => $order_info['id']], 'refunding_count');


        // 记录退款日志
        $logData = [
            'refund_id' => $refundId,
            'refund_status' => $role == 'store' ? TblOrderRefundEnum::STATUS_STORE_APPLYING : TblOrderRefundEnum::STATUS_USER_APPLYING,
            'message' => $role == 'store' ? '店铺发起退款申请' : '用户发起退款申请' . ',申请金额：' . $data['apply_amount'] . '元',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];

        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);

        return true;
    }



    // 修改退款 退款申请中以及 店铺拒绝退款 用户可以修改退款
    public function editRefund(array $refund_info, array $data, string $role, int $user_id): bool
    {

        if ($role == 'user') {
            $user_available_actions = $this->getUserRefundActions($refund_info);
            if (!in_array('edit_refund', $user_available_actions)) {
                throw new PermissionException('用户没有修改退款权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        // 退款
        $order_goods_id = $refund_info['order_goods_id'];
        if ($order_goods_id == 0) {
            // 全额退款不允许修改
            $order_info = (new TblOrderDao())->getOrderInfoById($refund_info['order_id']);
            if ($data['apply_amount'] > $order_info['pay_amount']) {
                throw new CommonException('当前退款金额大于订单金额' . $order_info['pay_amount'] . '元');
            }
        } else {
            // 部分退款
            $order_goods_info = (new TblOrderGoodsDao())->getOrderGoodsInfoById($order_goods_id);
            if ($data['apply_amount'] > $order_goods_info['pay_price'] * $order_goods_info['goods_num']) {
                throw new CommonException('当前退款金额大于订单商品实际支付金额' . $order_goods_info['pay_price'] * $order_goods_info['goods_num'] . '元');
            }
        }

        // 修改退款信息
        $refund_data = [
            'apply_amount' => $data['apply_amount'],
            'refund_explain' => $data['refund_explain'],
            // 店铺拒绝，用户修改退款申请 ，退款状态再次进入用户申请中
            'refund_status' => TblOrderRefundEnum::STATUS_USER_APPLYING,
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);

        // 增加正在退款的订单数量
        (new TblOrderDao())->setOrderInc(['id' => $refund_info['order_id']], 'refunding_count');

        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_USER_APPLYING, // 用户申请中
            'message' => '修改退款申请。申请原因：' . $data['refund_explain'] . ',申请金额：' . $data['apply_amount'] . '元',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];

        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);

        return true;
    }




    /**
     * 店铺同意退款(只处理订单的退款状态，金额的退款由 processRefund 方法处理)
     * 退款金额使用用户申请的金额，卖家不能修改
     * 
     * @param array $refund_info 退款信息
     * @param array $data 退款数据（包含 agree_reason）
     * @param int $user_id 用户ID
     * @return bool 操作结果
     */
    public function storeAgreeRefund(array $refund_info, array $data, int $user_id): bool
    {
        $role = 'store';
        if ($role == 'store') {
            $store_available_actions = $this->getStoreRefundActions($refund_info);
            if (!in_array('agree_refund', $store_available_actions)) {
                throw new PermissionException('店铺没有同意退款权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        // 获取同意备注（可选）
        $agree_reason = $data['agree_reason'] ?? '';

        // 构建日志消息
        $log_message = '店铺同意退款';
        if (!empty($agree_reason)) {
            $log_message .= '，备注：' . $agree_reason;
        }

        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_STORE_AGREED,
            'message' => $log_message,
            'create_role' => 'store',
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);


        // 更新退款状态
        $refund_data = [
            // 如果是仅退款类型，直接进入退款处理流程
            'refund_status' => $refund_info['refund_type'] == TblOrderRefundEnum::TYPE_ONLY_REFUND ? TblOrderRefundEnum::STATUS_REFUND_PROCESSING : TblOrderRefundEnum::STATUS_STORE_AGREED,
            'agree_time' => time(),
            'agree_reason' => $agree_reason,
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);


        // 如果是仅退款类型
        if ($refund_info['refund_type'] == TblOrderRefundEnum::TYPE_ONLY_REFUND) {
            // 如果订单状态为未收货状态，订单自动确认收货，为完成状态
            $order_info = (new TblOrderDao())->getOrderInfoById($refund_info['order_id']);
            if (in_array($order_info['order_status'], [
                TblOrderEnum::ORDER_STATUS_ACCEPTED,
                TblOrderEnum::ORDER_STATUS_CONFIRMED,
            ])) {
                (new DeshangTblOrderService())->changeTblOrderConfirm($order_info, 'system', 0);
            }
        }



        return true;
    }

    /**
     * 店铺拒绝退款
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function storeRejectRefund(array $refund_info, array $data, int $user_id): bool
    {

        $role = 'store';
        if ($role == 'store') {
            $store_available_actions = $this->getStoreRefundActions($refund_info);
            if (!in_array('reject_refund', $store_available_actions)) {
                throw new StateException('当前退款状态不允许拒绝退款');
            }
        } else {
            throw new CommonException('非法操作');
        }


        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_STORE_REJECTED,
            'reject_reason' => $data['reject_reason'] ?? '',
            'reject_time' => time(),
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);


        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_STORE_REJECTED,
            'message' => '店铺拒绝退款，原因：' . ($data['reject_reason'] ?? ''),
            'create_role' => 'store',
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);


        // 退款被拒绝，减少正在退款的订单数量
        (new TblOrderDao())->setOrderDec(['id' => $refund_info['order_id']], 'refunding_count');

        return true;
    }

    /**
     * 用户退货
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function userReturnGoods(array $refund_info, array $data, int $user_id): bool
    {

        $role = 'user';
        if ($role == 'user') {
            $user_available_actions = $this->getUserRefundActions($refund_info);
            if (!in_array('return_goods', $user_available_actions)) {
                throw new StateException('当前退款状态不允许退货');
            }
        } else {
            throw new CommonException('非法操作');
        }



        // 更新退款状态
        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_USER_RETURNED,
            'express_company' => $data['express_company'] ?? '',
            'express_number' => $data['express_number'] ?? '',
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);


        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_USER_RETURNED,
            'message' => '用户已退货，物流公司：' . ($data['express_company'] ?? '') . '，物流单号：' . ($data['express_number'] ?? ''),
            'create_role' => 'user',
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);

        return true;
    }

    /**
     * 店铺确认收到退货（只处理订单的退款状态，金额的退款由 processRefund 方法处理）
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function storeReceiveGoods(array $refund_info, int $user_id): bool
    {

        $role = 'store';
        if ($role == 'store') {
            $store_available_actions = $this->getStoreRefundActions($refund_info);
            if (!in_array('receive_goods', $store_available_actions)) {
                throw new StateException('当前退款状态不允许确认收货');
            }
        } else {
            throw new CommonException('非法操作');
        }


        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_STORE_RECEIVED,
            'message' => '店铺已收到退货',
            'create_role' => 'store',
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);


        // 更新退款状态
        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_PROCESSING,
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);


        // 如果订单状态为未收货状态，订单自动确认收货，为完成状态
        $order_info = (new TblOrderDao())->getOrderInfoById($refund_info['order_id']);
        if (in_array($order_info['order_status'], [
            TblOrderEnum::ORDER_STATUS_ACCEPTED,
            TblOrderEnum::ORDER_STATUS_CONFIRMED,
        ])) {
            (new DeshangTblOrderService())->changeTblOrderConfirm($order_info, 'system', 0);
        }


        return true;
    }

    /**
     * 处理退款  (一般情况订单退款，与处理退款(金额原路返回或退回余额) 分为两个单独流程) 
     * 在处理退款前 ,已发货的订单状态为已完成(ORDER_STATUS_COMPLETED)， 未发货的订单状态为已取消(ORDER_STATUS_CANCELLED)。
     * (在原路退回失败时,需要在后台重新发起，)
     * 
     * @param array $refund_info 退款信息
     * @param string $role 角色
     * @param int $user_id 用户ID
     * @return bool 操作结果
     */
    public function processRefund(array $refund_info, string $role, int $user_id): bool
    {

        // 验证退款状态  只有退款状态为处理中或退款失败时，才允许处理退款
        if (!in_array($refund_info['refund_status'], [
            TblOrderRefundEnum::STATUS_REFUND_PROCESSING,
            TblOrderRefundEnum::STATUS_REFUND_FAILED,
        ])) {
            throw new StateException('当前退款状态不允许此操作');
        }

        // 验证当前退款的订单状态，未完成则先自动完成，因如果余额退款，商户需要扣除退款金额，否则失败
        $order_info = (new TblOrderDao())->getOrderInfoById($refund_info['order_id']);

        // 如果订单状态不是  已完成或已取消状态 则不可处理退款(一般退款同意后 会先完成，因为出现部分退款，需要先完成订单，再退款)
        if ($order_info['order_status'] != TblOrderEnum::ORDER_STATUS_COMPLETED && $order_info['order_status'] != TblOrderEnum::ORDER_STATUS_CANCELLED) {
            throw new StateException('当前订单状态不允许处理退款');
        }


        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_PROCESSING,
            'message' => '退款处理中',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);




        // 获取当前系统支持的退款方式  , REFUND_METHOD_ORIGINAL 原路退回  REFUND_METHOD_BALANCE 余额退款
        // 当支付渠道是 微信 或 支付宝 时， 退款方式为 原路退回 ， 其他为 余额退款
        $refund_method = in_array($order_info['pay_channel'], [TradePaymentConfigEnum::CHANNEL_WECHAT, TradePaymentConfigEnum::CHANNEL_ALIPAY]) ? TblOrderRefundEnum::REFUND_METHOD_ORIGINAL : TblOrderRefundEnum::REFUND_METHOD_BALANCE;


        // 根据退款方式处理退款(给用户退款)
        switch ($refund_method) {
            case TblOrderRefundEnum::REFUND_METHOD_ORIGINAL:


                //处理原路退回
                $trade = ThirdPartyLoader::trade($order_info['pay_merchant_id'], $order_info['pay_channel'], $order_info['pay_scene'], 'refund', '', '');
                $refund_data = [
                    'out_trade_no' => $order_info['out_trade_no'],
                    'total_amount' => $order_info['pay_amount'],
                    'refund_amount' => $refund_info['refund_amount'],
                    'out_refund_no' => $refund_info['out_refund_no'],
                ];

                $is_refund_success = $trade->refund($refund_data);

                // 原路退回 插入记录
                $trade_refund_log_data = [
                    'user_id' => $order_info['user_id'],
                    'source_type' => TradeRefundEnum::SOURCE_TYPE_REFUND,
                    'source_id' => $refund_info['id'],
                    'out_trade_no' => $order_info['out_trade_no'],
                    'trade_no' => $order_info['trade_no'],
                    'out_refund_no' => $refund_info['out_refund_no'],
                    'pay_amount' => $order_info['pay_amount'],
                    'refund_amount' => $refund_info['refund_amount'],
                    'refund_channel' => $order_info['pay_channel'],
                    'refund_scene' => $order_info['pay_scene'],
                    'refund_status' => $is_refund_success ? TradeRefundEnum::REFUND_STATUS_SUCCESS : TradeRefundEnum::REFUND_STATUS_FAILED,
                    'refund_time' => time(),
                ];
                (new TradeRefundLogDao())->createTradeRefundLog($trade_refund_log_data);

                break;

            case TblOrderRefundEnum::REFUND_METHOD_BALANCE:
                // 增加用户余额
                $user_balance_data = [
                    'change_mode' => UserBalanceEnum::MODE_INCREASE,
                    'change_type' => UserBalanceEnum::TYPE_REFUND,
                    'change_amount' => $refund_info['refund_amount'],
                    'user_id' => $refund_info['user_id'],
                    'related_id' => $refund_info['id'],
                    'change_desc' => '订单退款' . '(订单ID：' . $refund_info['order_id'] . ')',
                ];
                (new DeshangUserBalanceService())->modifyUserBalance($user_balance_data);
                // 原路退回成功
                $is_refund_success = true;

                break;



            case TblOrderRefundEnum::REFUND_METHOD_MANUAL:

                throw new CommonException('当前人工处理方式功能预留');


                break;

            default:
                throw new CommonException('不支持的退款方式');
        }




        // 修改退款方式
        $refund_info['refund_method'] = $refund_method;
        if ($is_refund_success) {


            // 是否需要 减少商户金额
            $is_merchant_pay = true;

            // 退款成功后，对商户进行退款
            // 在确认收货后，会把实付金额增加给商户
            // 1. 如果订单状态为 ORDER_STATUS_CANCELLED 则不扣除商户余额,因为 没有确认收货 商户没有收款到，只是平台才收到款
            // 2. 如果是商户收款，确认收货， 商户预存款也不会增加。
            if ($order_info['order_status'] == TblOrderEnum::ORDER_STATUS_CANCELLED) {
                $is_merchant_pay = false;
            } else if ($order_info['pay_merchant_id'] != 0) {
                // 商户收款，确认收货之后，商户预存款也不会增加。
                $is_merchant_pay = false;
            }
            if ($is_merchant_pay) {
                // 获取店铺所属的商户ID
                $merchant_info = (new TblStoreDao())->getStoreInfoById($refund_info['store_id'], 'merchant_id');
                // 商户扣除退款金额
                $merchant_balance_data = [
                    'change_mode' => MerchantBalanceEnum::MODE_DECREASE,
                    'change_type' => MerchantBalanceEnum::TYPE_REFUND,
                    'change_amount' => $refund_info['refund_amount'],
                    'merchant_id' => $merchant_info['merchant_id'],
                    'store_id' => $refund_info['store_id'],
                    'related_id' => $refund_info['id'],
                    'change_desc' => '订单退款' . '(订单ID：' . $refund_info['order_id'] . ')',
                ];
                (new DeshangMerchantBalanceService())->modifyMerchantBalance($merchant_balance_data);
            }


            // 结算完成是允许在一定时间内允许退款， 出现退款需要修改 订单结算数据
            $order_finance_data = [
                'store_amount' => Db::raw('store_amount - ' . $refund_info['refund_amount']),
                'refund_amount' => Db::raw('refund_amount + ' . $refund_info['refund_amount']),
                'settle_time' => time(),
            ];
            (new TblOrderFinanceDao())->updateOrderFinance(['order_id' => $order_info['id']], $order_finance_data);

            // 如果订单状态不为 ORDER_STATUS_CANCELLED 则表示商户已扣除过 推广佣金，退款需要返还给商户
            if ($order_info['order_status'] != TblOrderEnum::ORDER_STATUS_CANCELLED) {
                // 退回佣金 及修改 分销商订单状态
                (new DeshangDistributorOrderService())->refundDistributorOrder($refund_info, $merchant_info['merchant_id']);
            }




            // 退款成功 处理其他状态
            $this->refundSuccess($refund_info, $role, $user_id);
        } else {
            // 退款失败 
            $this->refundFailed($refund_info, $role, $user_id);
        }



        return true;
    }

    /**
     * 退款成功
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function refundSuccess(array $refund_info, string $role, int $user_id): bool
    {

        // 获取订单信息
        $orderInfo = (new TblOrderDao())->getOrderInfoById($refund_info['order_id']);
        if (empty($orderInfo)) {
            throw new CommonException('订单不存在');
        }


        // 更新退款状态为退款成功
        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_SUCCESS,
            'success_time' => time(),
            'refund_method' => $refund_info['refund_method'],
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);

        // 记录退款日志
        $refund_log_data = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_SUCCESS,
            'message' => '退款成功,' . $refund_info['refund_amount'] . '元,已' . TblOrderRefundEnum::getRefundMethodDesc($refund_info['refund_method']),
            'create_role' => $role,
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($refund_log_data);



        // 更新订单退款状态为已退款
        $total_refund_amount = $refund_info['refund_amount'] + $orderInfo['refund_amount'];
        $order_data = [
            // 0无退款 1部分退款 2全部退款
            'refund_status' => $total_refund_amount == $orderInfo['pay_amount'] ? TblOrderEnum::REFUND_STATUS_FULL_REFUNDED : TblOrderEnum::REFUND_STATUS_PARTIAL_REFUNDED,
            'refund_amount' => $total_refund_amount,
            'refunding_count' => $orderInfo['refunding_count'] - 1,
        ];
        (new TblOrderDao())->updateOrder(['id' => $orderInfo['id']], $order_data);


        // 生成订单日志
        $order_log_data = array(
            'order_id' => $orderInfo['id'],
            'order_status' => $orderInfo['order_status'],
            'message' => '退款成功,' . $refund_info['refund_amount'] . '元,已' . TblOrderRefundEnum::getRefundMethodDesc($refund_info['refund_method']),
            'create_role' => $role,
            'create_by' => $user_id,
        );
        (new TblOrderLogDao())->createOrderLog($order_log_data);




        return true;
    }

    /**
     * 退款失败  (可多次发起处理退款,如果失败,则需要重新发起退款)
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function refundFailed(array $refund_info, string $role, int $user_id): bool
    {


        // 更新退款状态
        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_FAILED,
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);

        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_REFUND_FAILED,
            'message' => '退款失败',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);


        return true;
    }

    /**
     * 关闭退款
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function closeRefund(array $refund_info, string $role, int $user_id): bool
    {


        // 验证退款状态是否允许关闭
        if (in_array($refund_info['refund_status'], [
            TblOrderRefundEnum::STATUS_REFUND_SUCCESS,
            TblOrderRefundEnum::STATUS_CLOSED,
            TblOrderRefundEnum::STATUS_CANCELED
        ])) {
            throw new StateException('当前退款状态不允许此操作');
        }
        // 更新退款状态
        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_CLOSED,
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);


        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_CLOSED,
            'message' => '退款关闭',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);

        // 退款关闭，减少正在退款的订单数量
        (new TblOrderDao())->setOrderDec(['id' => $refund_info['order_id']], 'refunding_count');

        return true;
    }

    /**
     * 取消退款
     * 
     * @param array $data 退款数据
     * @return bool 操作结果
     */
    public function cancelRefund(array $refund_info, string $role, int $user_id): bool
    {
        if ($role == 'user') {
            $user_available_actions = $this->getUserRefundActions($refund_info);
            if (!in_array('cancel_refund', $user_available_actions)) {
                throw new StateException('当前退款状态不允许取消退款');
            }
        } else {
            throw new CommonException('非法操作');
        }


        // 更新退款状态
        $refund_data = [
            'refund_status' => TblOrderRefundEnum::STATUS_CANCELED,
        ];
        (new TblOrderRefundDao())->updateOrderRefund(['id' => $refund_info['id']], $refund_data);


        // 记录退款日志
        $logData = [
            'refund_id' => $refund_info['id'],
            'refund_status' => TblOrderRefundEnum::STATUS_CANCELED,
            'message' => '退款取消',
            'create_role' => $role,
            'create_uid' => $user_id,
        ];
        (new TblOrderRefundLogDao())->createOrderRefundLog($logData);

        // 退款取消，减少正在退款的订单数量
        (new TblOrderDao())->setOrderDec(['id' => $refund_info['order_id']], 'refunding_count');

        return true;
    }
}
