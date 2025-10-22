<?php

namespace app\common\dao\pointsGoods;

use app\common\dao\BaseDao;
use app\common\model\pointsGoods\PointsGoodsOrderLogModel;

/**
 * 积分商品订单日志数据访问对象
 * 
 * 负责积分商品订单操作日志的数据库交互操作
 */
class PointsGoodsOrderLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化PointsGoodsOrderLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new PointsGoodsOrderLogModel();
    }

    /**
     * 创建积分商品订单日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createPointsGoodsOrderLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除积分商品订单日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deletePointsGoodsOrderLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新积分商品订单日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updatePointsGoodsOrderLog(array $condition, array $data): int
    {
        return $this->model->where($condition)->update($data);
    }

    /**
     * 获取积分商品订单日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段
     * @return array|null 日志信息
     */
    public function getPointsGoodsOrderLogInfo(array $condition, string $field = '*'): ?array
    {
        return $this->model->where($condition)->field($field)
        ->append(['order_status_desc'])
        ->findOrEmpty()->toArray();
    }

    /**
     * 获取积分商品订单日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段
     * @param string $order 排序
     * @return array 日志列表
     */
    public function getPointsGoodsOrderLogList(array $condition, string $field = '*', string $order = 'create_at asc'): array
    {
        return $this->model->where($condition)->field($field)
        ->append(['order_status_desc'])
        ->order($order)->select()->toArray();
    }

    /**
     * 获取积分商品订单日志分页
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段
     * @param string $order 排序
     * @return array 分页数据
     */
    public function getPointsGoodsOrderLogPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)
        ->append(['order_status_desc'])
        ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取积分商品订单日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getPointsGoodsOrderLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
}
