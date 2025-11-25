<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsCategoryModel;

/**
 * 商品分类数据访问对象
 * 
 * 负责商品分类的数据库交互操作
 */
class TblGoodsCategoryDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsCategoryModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsCategoryModel();
    }
    
    /**
     * 创建商品分类
     * 
     * @param array $data 分类数据
     * @return int 新创建的分类ID
     */
    public function createGoodsCategory(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除商品分类
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsCategory(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品分类
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsCategory(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取商品分类列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分类列表
     */
    public function getGoodsCategoryList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商品分类分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getGoodsCategoryPages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品分类信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 分类信息
     */
    public function getGoodsCategoryInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品分类信息
     * 
     * @param int $id 分类ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 分类信息
     */
    public function getGoodsCategoryById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品分类数量
     * 
     * @param array $condition 查询条件
     * @return int 分类数量
     */
    public function getGoodsCategoryCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品分类列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsCategoryColumn(array $condition, string $column): array
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
        $info = $this->getGoodsCategoryById($id);
        if (!empty($info)) {
            $path[] = $info;
            if ($info['parent_id'] > 0) {
                $this->getParentPath($info['parent_id'], $path);
            }
        }
    }
    

}
