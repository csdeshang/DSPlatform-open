<?php

namespace app\deshang\service\pointsGoods;

use think\facade\Db;
use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\PermissionException;
use app\deshang\service\BaseDeshangService;
use app\common\dao\pointsGoods\PointsGoodsOrderDao;
use app\common\dao\pointsGoods\PointsGoodsDao;
use app\common\dao\pointsGoods\PointsGoodsOrderLogDao;
use app\common\enum\pointsGoods\PointsGoodsOrderEnum;
use app\common\enum\user\UserPointsEnum;
use app\deshang\service\user\DeshangUserPointsService;

/**
 * 积分商品订单服务 - 通用服务层
 */
class DeshangPointsGoodsOrderService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取用户可操作的积分商品订单状态
     * cancel 用户 取消订单
     * confirm 用户 确认收货
     * evaluate 用户 评价
     */
    public function getUserAvailableActions(array $order_info): array
    {
        $actions = [];

        // 订单已删除，则没有操作权限
        if ($order_info['is_deleted'] == 1) {
            return $actions;
        }

        // 订单状态是取消，没有任何操作
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_CANCELLED) {
            return $actions;
        }

        // 订单状态是待发货，用户可以取消
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_PENDING) {
            $actions[] = 'cancel';
        }

        // 订单状态是已发货，用户可以确认收货
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_SHIPPED) {
            $actions[] = 'confirm';
        }

        // 订单状态是已收货，用户可以评价
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_RECEIVED) {
            if ($order_info['is_evaluate'] == 0) {
                // 未评价，可以评价
                $actions[] = 'evaluate';
            }
        }

        return $actions;
    }

    /**
     * 获取管理员可操作的积分商品订单状态
     * cancel 管理员 取消订单
     * ship 管理员 发货
     * confirm 管理员 确认收货
     */
    public function getAdminAvailableActions(array $order_info): array
    {
        $actions = [];

        // 订单已删除，则没有操作权限
        if ($order_info['is_deleted'] == 1) {
            return $actions;
        }

        // 订单状态是取消，没有任何操作
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_CANCELLED) {
            return $actions;
        }

        // 订单状态是待发货，管理员可以发货或取消
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_PENDING) {
            $actions[] = 'ship';
            $actions[] = 'cancel';
        }

        // 订单状态是已发货，管理员可以确认收货
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_SHIPPED) {
            $actions[] = 'confirm';
        }

        // 订单状态是已收货，没有任何操作
        if ($order_info['order_status'] == PointsGoodsOrderEnum::ORDER_STATUS_RECEIVED) {
            return $actions;
        }

        return $actions;
    }

    /**
     * 取消积分商品订单
     * 
     * @param array $order_info 订单信息
     * @param string $role 操作角色 user|admin
     * @param int $user_id 操作用户ID
     * @return bool 是否取消成功
     */
    public function cancelPointsGoodsOrder(array $order_info, string $role, int $user_id = 0): bool
    {
        // 验证操作权限
        if ($role == 'user') {
            $user_available_actions = $this->getUserAvailableActions($order_info);
            if (!in_array('cancel', $user_available_actions)) {
                throw new PermissionException('用户没有取消订单权限');
            }
        } else if ($role == 'admin') {
            $admin_available_actions = $this->getAdminAvailableActions($order_info);
            if (!in_array('cancel', $admin_available_actions)) {
                throw new PermissionException('管理员没有取消订单权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        // 更新订单状态为已取消
        (new PointsGoodsOrderDao())->updatePointsGoodsOrder(['id' => $order_info['id']], [
            'order_status' => PointsGoodsOrderEnum::ORDER_STATUS_CANCELLED,
            'update_at' => time()
        ]);

        // 创建订单日志
        $logData = [
            'order_id' => $order_info['id'],
            'order_status' => PointsGoodsOrderEnum::ORDER_STATUS_CANCELLED,
            'message' => ($role == 'user' ? '用户' : '管理员') . '取消积分商品订单',
            'create_role' => $role,
            'create_by' => (string)$user_id,
            'create_at' => time(),
            'extra' => json_encode(['order_sn' => $order_info['order_sn']])
        ];
        (new PointsGoodsOrderLogDao())->createPointsGoodsOrderLog($logData);

        // 用户积分退回
        $points_data = [
            'user_id' => $order_info['user_id'],
            'related_id' => $order_info['id'],
            'change_type' => UserPointsEnum::TYPE_POINTS_GOODS_ORDER,
            'change_mode' => UserPointsEnum::MODE_INCREASE,
            'change_num' => $order_info['total_points'],
            'change_desc' => '积分商品订单取消',
        ];
        (new DeshangUserPointsService())->modifyUserPoints($points_data);

        // 恢复积分商品库存
        (new PointsGoodsDao())->setPointsGoodsInc(['id' => $order_info['goods_id']], 'stock_num', $order_info['exchange_num']);

        // 减少兑换数量
        (new PointsGoodsDao())->setPointsGoodsDec(['id' => $order_info['goods_id']], 'exchange_num', $order_info['exchange_num']);


        return true;
    }

    /**
     * 发货积分商品订单
     * 
     * @param array $order_info 订单信息
     * @param string $role 操作角色 admin
     * @param int $user_id 操作用户ID
     * @param array $extra 额外数据，包含快递公司、快递单号、备注等
     * @return bool 是否发货成功
     */
    public function shipPointsGoodsOrder(array $order_info, string $role, int $user_id = 0, array $extra = []): bool
    {
        // 验证操作权限
        if ($role == 'admin') {
            $admin_available_actions = $this->getAdminAvailableActions($order_info);
            if (!in_array('ship', $admin_available_actions)) {
                throw new PermissionException('管理员没有发货权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        // 验证发货参数
        if (empty($extra['delivery_method'])) {
            throw new CommonException('配送方式不能为空');
        }
        if (!in_array($extra['delivery_method'], [PointsGoodsOrderEnum::DELIVERY_METHOD_EXPRESS, PointsGoodsOrderEnum::DELIVERY_METHOD_DELIVERY])) {
            throw new CommonException('配送方式无效');
        }


        // 更新订单状态为已发货
        $updateData = [
            'order_status' => PointsGoodsOrderEnum::ORDER_STATUS_SHIPPED,
            'delivery_method' => $extra['delivery_method'],
            'express_company' => $extra['express_company'] ?? '',
            'express_no' => $extra['express_no'] ?? '',
            'express_time' => time(),
            'update_at' => time(),
        ];

        if (!empty($extra['remark'])) {
            $updateData['remark'] = $extra['remark'];
        }

        (new PointsGoodsOrderDao())->updatePointsGoodsOrder(['id' => $order_info['id']], $updateData);

        // 创建订单日志
        $deliveryMethodText = PointsGoodsOrderEnum::getDeliveryMethodDesc($extra['delivery_method']);
        $logMessage = '管理员发货，配送方式：' . $deliveryMethodText;

        if ($extra['delivery_method'] == PointsGoodsOrderEnum::DELIVERY_METHOD_EXPRESS) {
            if (!empty($extra['express_no'])) {
                $logMessage .= '，快递单号：' . $extra['express_no'];
            }
            if (!empty($extra['express_company'])) {
                $logMessage .= '，快递公司：' . $extra['express_company'];
            }
        }

        $logData = [
            'order_id' => $order_info['id'],
            'order_status' => PointsGoodsOrderEnum::ORDER_STATUS_SHIPPED,
            'message' => $logMessage,
            'create_role' => $role,
            'create_by' => (string)$user_id,
            'create_at' => time(),
            'extra' => json_encode([
                'order_sn' => $order_info['order_sn'],
                'delivery_method' => $extra['delivery_method'],
                'express_company' => $extra['express_company'] ?? '',
                'express_no' => $extra['express_no'],
                'remark' => $extra['remark'] ?? ''
            ])
        ];
        (new PointsGoodsOrderLogDao())->createPointsGoodsOrderLog($logData);


        return true;
    }

    /**
     * 确认收货积分商品订单
     * 
     * @param array $order_info 订单信息
     * @param string $role 操作角色 user|admin
     * @param int $user_id 操作用户ID
     * @return bool 是否确认收货成功
     */
    public function confirmPointsGoodsOrder(array $order_info, string $role, int $user_id = 0): bool
    {
        // 验证操作权限
        if ($role == 'user') {
            $user_available_actions = $this->getUserAvailableActions($order_info);
            if (!in_array('confirm', $user_available_actions)) {
                throw new PermissionException('用户没有确认收货权限');
            }
        } else if ($role == 'admin') {
            $admin_available_actions = $this->getAdminAvailableActions($order_info);
            if (!in_array('confirm', $admin_available_actions)) {
                throw new PermissionException('管理员没有确认收货权限');
            }
        } else {
            throw new CommonException('非法操作');
        }

        // 更新订单状态为已收货
        (new PointsGoodsOrderDao())->updatePointsGoodsOrder(['id' => $order_info['id']], [
            'order_status' => PointsGoodsOrderEnum::ORDER_STATUS_RECEIVED,
            'receive_time' => time(),
            'update_at' => time()
        ]);

        // 创建订单日志
        $logData = [
            'order_id' => $order_info['id'],
            'order_status' => PointsGoodsOrderEnum::ORDER_STATUS_RECEIVED,
            'message' => ($role == 'user' ? '用户' : '管理员') . '确认收货',
            'create_role' => $role,
            'create_by' => (string)$user_id,
            'create_at' => time(),
            'extra' => json_encode(['order_sn' => $order_info['order_sn']])
        ];
        (new PointsGoodsOrderLogDao())->createPointsGoodsOrderLog($logData);


        return true;
    }
}
