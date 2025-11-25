<?php

namespace app\adminapi\service\pointsGoods;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\pointsGoods\PointsGoodsOrderDao;
use app\common\dao\pointsGoods\PointsGoodsOrderLogDao;
use app\deshang\exceptions\CommonException;
use app\deshang\service\pointsGoods\DeshangPointsGoodsOrderService;
use app\deshang\utils\SearchHelper;

use think\facade\Db;

class PointsGoodsOrderService extends BaseAdminService
{
    /**
     * 获取积分兑换订单分页列表
     * 
     * @param array $data 查询参数
     * @return array 分页数据
     */
    public function getPointsGoodsOrderPages($data)
    {
        $condition = [];
        
        // 订单号搜索
        if (isset($data['order_sn']) && !empty($data['order_sn'])) {
            $condition[] = ['order_sn', 'like', '%' . $data['order_sn'] . '%'];
        }
        
        // 用户ID筛选
        if (isset($data['user_id']) && $data['user_id'] > 0) {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 商品ID筛选
        if (isset($data['goods_id']) && $data['goods_id'] > 0) {
            $condition[] = ['goods_id', '=', $data['goods_id']];
        }
        
        // 商品名称模糊搜索
        if (isset($data['goods_name']) && !empty($data['goods_name'])) {
            $condition[] = ['goods_name', 'like', '%' . $data['goods_name'] . '%'];
        }
        
        // 订单状态筛选
        if (isset($data['order_status']) && in_array($data['order_status'], [0, 10, 20, 30, 40])) {
            $condition[] = ['order_status', '=', $data['order_status']];
        }
        
        // 收货人姓名模糊搜索
        if (isset($data['receiver_name']) && !empty($data['receiver_name'])) {
            $condition[] = ['receiver_name', 'like', '%' . $data['receiver_name'] . '%'];
        }
        
        // 收货人手机号搜索
        if (isset($data['receiver_mobile']) && !empty($data['receiver_mobile'])) {
            $condition[] = ['receiver_mobile', 'like', '%' . $data['receiver_mobile'] . '%'];
        }
        
        // 未删除
        $condition[] = ['is_deleted', '=', 0];

        $result = (new PointsGoodsOrderDao())->getPointsGoodsOrderPages($condition);
        
        // 为每个订单添加管理员可执行操作
        foreach ($result['data'] as &$order) {
            $order['admin_available_actions'] = (new DeshangPointsGoodsOrderService())->getAdminAvailableActions($order);
        }
        
        return $result;
    }

    /**
     * 获取积分兑换订单详情
     * 
     * @param int $id 订单ID
     * @return array 订单详情
     */
    public function getPointsGoodsOrderInfo(int $id)
    {
        $result = (new PointsGoodsOrderDao())->getPointsGoodsOrderInfoById($id);
        
        // 添加管理员可执行操作
        if ($result) {
            $result['admin_available_actions'] = (new DeshangPointsGoodsOrderService())->getAdminAvailableActions($result);
        }
        
        return $result;
    }

    /**
     * 获取积分兑换订单日志
     * 
     * @param int $id 订单ID
     * @return array 订单日志列表
     */
    public function getPointsGoodsOrderLogs(int $id)
    {
        $condition = [['order_id', '=', $id]];
        return (new PointsGoodsOrderLogDao())->getPointsGoodsOrderLogList($condition);
    }

    /**
     * 更新积分兑换订单
     * 
     * @param array $data 订单数据
     * @return bool 是否更新成功
     */
    public function updatePointsGoodsOrder(array $data)
    {
        // 设置更新时间
        $data['update_at'] = time();

        $condition = [['id', '=', $data['id']]];
        return (new PointsGoodsOrderDao())->updatePointsGoodsOrder($condition, $data);
    }

    /**
     * 取消积分兑换订单
     * 
     * @param int $id 订单ID
     * @return bool 是否取消成功
     */
    public function cancelPointsGoodsOrder(int $id)
    {
        $order = (new PointsGoodsOrderDao())->getPointsGoodsOrderInfoById($id);
        if (empty($order)) {
            throw new CommonException('订单不存在');
        }

        // 使用通用服务处理取消订单
        Db::startTrans();
        try {
            $result = (new DeshangPointsGoodsOrderService())->cancelPointsGoodsOrder($order, 'admin', $this->user_id);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }

        return $result;
    }

    /**
     * 发货积分兑换订单
     * 
     * @param int $id 订单ID
     * @param array $data 发货数据
     * @return bool 是否发货成功
     */
    public function shipPointsGoodsOrder(int $id, array $data)
    {
        $order = (new PointsGoodsOrderDao())->getPointsGoodsOrderInfoById($id);
        if (empty($order)) {
            throw new CommonException('订单不存在');
        }
        Db::startTrans();
        try {
            // 使用通用服务处理发货
            $result = (new DeshangPointsGoodsOrderService())->shipPointsGoodsOrder($order, 'admin', $this->user_id, $data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
        
        return $result;
    }

    /**
     * 确认收货积分兑换订单
     * 
     * @param int $id 订单ID
     * @return bool 是否确认收货成功
     */
    public function confirmPointsGoodsOrder(int $id)
    {
        $order = (new PointsGoodsOrderDao())->getPointsGoodsOrderInfoById($id);
        if (empty($order)) {
            throw new CommonException('订单不存在');
        }

        Db::startTrans();
        try {
            // 使用通用服务处理确认收货
            $result = (new DeshangPointsGoodsOrderService())->confirmPointsGoodsOrder($order, 'admin', $this->user_id);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }

        return $result;
    }

    /**
     * 删除积分兑换订单
     * 
     * @param int $id 订单ID
     * @return bool 是否删除成功
     */
    public function deletePointsGoodsOrder(int $id)
    {
        $condition = [['id', '=', $id]];
        return (new PointsGoodsOrderDao())->softDeletePointsGoodsOrder($condition);
    }

}
