<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreGoodsCategoryModel;

/**
 * 店铺商品分类数据访问对象
 * 
 * 负责店铺商品分类的数据库交互操作
 */
class TblStoreGoodsCategoryDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreGoodsCategoryModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreGoodsCategoryModel();
    }
    
    /**
     * 创建店铺商品分类
     * 
     * @param array $data 分类数据
     * @return int 新创建的分类ID
     */
    public function createStoreGoodsCategory(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除店铺商品分类
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStoreGoodsCategory(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新店铺商品分类
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateStoreGoodsCategory(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取店铺商品分类列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分类列表
     */
    public function getStoreGoodsCategoryList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取店铺商品分类分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getStoreGoodsCategoryPages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条店铺商品分类信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 分类信息
     */
    public function getStoreGoodsCategoryInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取店铺商品分类信息
     * 
     * @param int $id 分类ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 分类信息
     */
    public function getStoreGoodsCategoryById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取店铺商品分类数量
     * 
     * @param array $condition 查询条件
     * @return int 分类数量
     */
    public function getStoreGoodsCategoryCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取店铺商品分类列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreGoodsCategoryColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
    /**
     * 获取分类所有父级路径
     * 
     * @param int $id 分类ID
     * @return array 父级路径数组
     */
    public function getCategoryPath(int $id): array
    {
        $path = [];
        $this->getParentPath($id, $path);
        return array_reverse($path);
    }
    
    /**
     * 递归获取父级路径
     * 
     * @param int $id 分类ID
     * @param array &$path 路径数组引用
     */
    private function getParentPath(int $id, array &$path): void
    {
        $info = $this->getStoreGoodsCategoryById($id);
        if (!empty($info)) {
            $path[] = $info;
            if ($info['parent_id'] > 0) {
                $this->getParentPath($info['parent_id'], $path);
            }
        }
    }
}
