<?php

namespace app\common\dao\distributor;

use app\common\dao\BaseDao;
use app\common\model\distributor\DistributorOrderModel;

/**
 * 分销订单数据访问对象
 * 
 * 负责分销订单的数据库交互操作
 */
class DistributorOrderDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化DistributorOrderModel模型实例
     */
    public function __construct()
    {
        $this->model = new DistributorOrderModel();
    }

    /**
     * 创建分销订单
     * 
     * @param array $data 订单数据
     * @return int 新创建的订单ID
     */
    public function createDistributorOrder(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除分销订单
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteDistributorOrder(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新分销订单
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateDistributorOrder(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取分销订单列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 订单列表
     */
    public function getDistributorOrderList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->append(['commission_type_desc', 'commission_status_desc'])
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }

    /**
     * 获取分销订单分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getDistributorOrderPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['commission_type_desc', 'commission_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }


    public function getWithRelDistributorOrderPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goods' => function ($query) {
                        $query->field('id,goods_name');
                    },
                ]
            )
            ->append(['commission_type_desc', 'commission_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }



    /**
     * 获取单条订单信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 订单信息
     */
    public function getDistributorOrderInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
            ->append(['commission_type_desc', 'commission_status_desc'])
            ->field($field)
            ->lock($lock)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 根据ID获取订单信息
     * 
     * @param int $id 订单ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 订单信息
     */
    public function getDistributorOrderById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)
            ->field($field)
            ->lock($lock)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 获取订单数量
     * 
     * @param array $condition 查询条件
     * @return int 订单数量
     */
    public function getDistributorOrderCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取订单列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getDistributorOrderColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    // 获取分销订单金额
    public function getDistributorOrderSum(array $condition, string $column): float
    {
        return $this->model->where($condition)->sum($column);
    }
}
