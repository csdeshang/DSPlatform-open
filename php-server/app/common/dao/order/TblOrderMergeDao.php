<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderMergeModel;

/**
 * 订单合并支付数据访问对象
 * 
 * 负责多店铺合并支付批次的数据库交互操作
 */
class TblOrderMergeDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderMergeModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderMergeModel();
    }
    
    /**
     * 创建订单合并支付
     * 
     * @param array $data 合并支付数据
     * @return int 新创建的合并支付ID
     */
    public function createOrderMerge(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单合并支付
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderMerge(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订单合并支付
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateOrderMerge(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取订单合并支付列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 合并支付列表
     */
    public function getOrderMergeList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取单条订单合并支付信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 合并支付信息
     */
    public function getOrderMergeInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取订单合并支付信息
     * 
     * @param int $id 合并支付ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 合并支付信息
     */
    public function getOrderMergeInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取订单合并支付数量
     * 
     * @param array $condition 查询条件
     * @return int 合并支付数量
     */
    public function getOrderMergeCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取订单合并支付列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderMergeColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
