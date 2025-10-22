<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderFinanceModel;

/**
 * 订单财务数据访问对象
 * 
 * 负责订单财务的数据库交互操作
 */
class TblOrderFinanceDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderFinanceModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderFinanceModel();
    }
    
    /**
     * 创建订单财务记录
     * 
     * @param array $data 财务数据
     * @return int 新创建的财务记录ID
     */
    public function createOrderFinance(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单财务记录
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderFinance(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订单财务记录
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateOrderFinance(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取订单财务记录列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 财务记录列表
     */
    public function getOrderFinanceList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取订单财务记录分页
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 财务记录分页
     */
    public function getOrderFinancePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条订单财务记录信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 财务记录信息
     */
    public function getOrderFinanceInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取订单财务记录信息
     * 
     * @param int $id 财务记录ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 财务记录信息
     */
    public function getOrderFinanceInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据订单ID获取财务记录信息
     * 
     * @param int $orderId 订单ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 财务记录信息
     */
    public function getOrderFinanceInfoByOrderId(int $orderId, string $field = '*'): array
    {
        return $this->model->where('order_id', $orderId)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取订单财务记录数量
     * 
     * @param array $condition 查询条件
     * @return int 财务记录数量
     */
    public function getOrderFinanceCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取订单财务记录列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderFinanceColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}