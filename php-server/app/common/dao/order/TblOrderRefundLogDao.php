<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderRefundLogModel;

/**
 * 订单退款日志数据访问对象
 * 
 * 负责订单退款日志的数据库交互操作
 */
class TblOrderRefundLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderRefundLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderRefundLogModel();
    }
    
    /**
     * 创建订单退款日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createOrderRefundLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单退款日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderRefundLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订单退款日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateOrderRefundLog(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取订单退款日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getOrderRefundLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取订单退款日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getOrderRefundLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条订单退款日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getOrderRefundLogInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取订单退款日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getOrderRefundLogInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取订单退款日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderRefundLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
