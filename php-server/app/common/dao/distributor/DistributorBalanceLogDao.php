<?php

namespace app\common\dao\distributor;

use app\common\dao\BaseDao;
use app\common\model\distributor\DistributorBalanceLogModel;

/**
 * 分销商余额日志数据访问对象
 * 
 * 负责分销商余额日志的数据库交互操作
 */
class DistributorBalanceLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化DistributorBalanceLogModel模型实例
     */
    public function __construct()
    {
        $this->model = new DistributorBalanceLogModel();
    }

    /**
     * 创建分销商余额日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createDistributorBalanceLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除分销商余额日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteDistributorBalanceLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新分销商余额日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateDistributorBalanceLog(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取分销商余额日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getDistributorBalanceLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }

    /**
     * 获取分销商余额日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getDistributorBalanceLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取带关联的分销商余额日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelDistributorBalanceLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with([
                'distributorUser' => function ($query) {
                    $query->field('id,username');
                }
            ])
            ->append(['change_type_desc', 'change_mode_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }
    

    /**
     * 获取单条余额日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getDistributorBalanceLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 根据ID获取余额日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getDistributorBalanceLogById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 获取余额日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getDistributorBalanceLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取余额日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getDistributorBalanceLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
    /**
     * 获取分销商余额日志总金额
     * 
     * @param array $condition 查询条件
     * @param string $field 金额字段名，默认为change_amount
     * @return float 总金额
     */
    public function getDistributorBalanceLogSum(array $condition, string $field = 'change_amount'): float
    {
        return $this->model->where($condition)->sum($field);
    }
}
