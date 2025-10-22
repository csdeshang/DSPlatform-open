<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysAreaModel;

/**
 * 系统地区数据访问对象
 * 
 * 负责系统地区的数据库交互操作
 */
class SysAreaDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysAreaModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysAreaModel();
    }
    
    /**
     * 创建地区
     * 
     * @param array $data 地区数据
     * @return int 新创建的地区ID
     */
    public function createArea(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除地区
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteArea(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新地区
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateArea(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取地区列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 地区列表
     */
    public function getAreaList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取地区分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 分页数据
     */
    public function getAreaPages(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条地区信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 地区信息
     */
    public function getAreaInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取地区信息
     * 
     * @param int $id 地区ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 地区信息
     */
    public function getAreaInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取地区数量
     * 
     * @param array $condition 查询条件
     * @return int 地区数量
     */
    public function getAreaCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取地区列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAreaColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    


}
