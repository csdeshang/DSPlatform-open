<?php

namespace app\common\dao\rider;

use app\common\dao\BaseDao;
use app\common\model\rider\RiderFeeRuleModel;

/**
 * 骑手配送费规则数据访问对象
 * 
 * 负责骑手配送费规则的数据库交互操作
 */
class RiderFeeRuleDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化RiderFeeRuleModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new RiderFeeRuleModel();
    }
    
    /**
     * 创建配送费规则
     * 
     * @param array $data 配送费规则数据
     * @return int 新创建的规则ID
     */
    public function createRiderFeeRule(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除配送费规则
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteRiderFeeRule(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新配送费规则
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateRiderFeeRule(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取配送费规则列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 配送费规则列表
     */
    public function getRiderFeeRuleList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取配送费规则分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getRiderFeeRulePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条配送费规则信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 配送费规则信息
     */
    public function getRiderFeeRuleInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取配送费规则信息
     * 
     * @param int $id 配送费规则ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 配送费规则信息
     */
    public function getRiderFeeRuleInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取配送费规则数量
     * 
     * @param array $condition 查询条件
     * @return int 配送费规则数量
     */
    public function getRiderFeeRuleCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取配送费规则列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getRiderFeeRuleColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
