<?php

namespace app\common\dao\distributor;

use app\common\dao\BaseDao;
use app\common\model\distributor\DistributorLevelModel;

/**
 * 分销等级数据访问对象
 * 
 * 负责分销等级的数据库交互操作
 */
class DistributorLevelDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化DistributorLevelModel模型实例
     */
    public function __construct()
    {
        $this->model = new DistributorLevelModel();
    }

    /**
     * 创建分销等级
     * 
     * @param array $data 等级数据
     * @return int 新创建的等级ID
     */
    public function createDistributorLevel(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除分销等级
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteDistributorLevel(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新分销等级
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateDistributorLevel(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取分销等级列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 等级列表
     */
    public function getDistributorLevelList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }

    /**
     * 获取分销等级分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getDistributorLevelPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条等级信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 等级信息
     */
    public function getDistributorLevelInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 根据ID获取等级信息
     * 
     * @param int $id 等级ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 等级信息
     */
    public function getDistributorLevelById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 获取等级数量
     * 
     * @param array $condition 查询条件
     * @return int 等级数量
     */
    public function getDistributorLevelCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取等级列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getDistributorLevelColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}