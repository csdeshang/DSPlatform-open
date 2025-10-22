<?php

namespace app\common\dao\distributor;

use app\common\dao\BaseDao;
use app\common\model\distributor\DistributorApplyModel;

/**
 * 分销商申请数据访问对象
 * 
 * 负责分销商申请的数据库交互操作
 */
class DistributorApplyDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化DistributorApplyModel模型实例
     */
    public function __construct()
    {
        $this->model = new DistributorApplyModel();
    }

    /**
     * 创建分销商申请
     * 
     * @param array $data 申请数据
     * @return int 新创建的申请ID
     */
    public function createDistributorApply(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除分销商申请
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteDistributorApply(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新分销商申请
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateDistributorApply(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取分销商申请列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 申请列表
     */
    public function getDistributorApplyList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }

    /**
     * 获取分销商申请分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getDistributorApplyPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['apply_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条申请信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 申请信息
     */
    public function getDistributorApplyInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->append(['apply_status_desc'])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 根据ID获取申请信息
     * 
     * @param int $id 申请ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 申请信息
     */
    public function getDistributorApplyById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 获取申请数量
     * 
     * @param array $condition 查询条件
     * @return int 申请数量
     */
    public function getDistributorApplyCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取申请列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getDistributorApplyColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
