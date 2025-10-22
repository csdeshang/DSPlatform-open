<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreCategoryModel;

/**
 * 店铺分类数据访问对象
 * 
 * 负责店铺分类的数据库交互操作
 */
class TblStoreCategoryDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreCategoryModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreCategoryModel();
    }
    
    /**
     * 创建店铺分类
     * 
     * @param array $data 分类数据
     * @return int 新创建的分类ID
     */
    public function createStoreCategory(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除店铺分类
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStoreCategory(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新店铺分类
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateStoreCategory(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取店铺分类列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分类列表
     */
    public function getStoreCategoryList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取店铺分类分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getStoreCategoryPages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条店铺分类信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 分类信息
     */
    public function getStoreCategoryInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取店铺分类信息
     * 
     * @param int $id 分类ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 分类信息
     */
    public function getStoreCategoryById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取店铺分类数量
     * 
     * @param array $condition 查询条件
     * @return int 分类数量
     */
    public function getStoreCategoryCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取店铺分类列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreCategoryColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
