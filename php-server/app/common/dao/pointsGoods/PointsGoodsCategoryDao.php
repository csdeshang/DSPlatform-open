<?php

namespace app\common\dao\pointsGoods;

use app\common\dao\BaseDao;
use app\common\model\pointsGoods\PointsGoodsCategoryModel;

/**
 * 积分商品分类数据访问对象
 * 
 * 负责积分商品分类的数据库交互操作
 */
class PointsGoodsCategoryDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化PointsGoodsCategoryModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new PointsGoodsCategoryModel();
    }

    /**
     * 创建积分商品分类
     * 
     * @param array $data 积分商品分类数据
     * @return int 新创建的积分商品分类ID
     */
    public function createPointsGoodsCategory(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除积分商品分类
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deletePointsGoodsCategory(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新积分商品分类
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updatePointsGoodsCategory(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取积分商品分类列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分类列表
     */
    public function getPointsGoodsCategoryList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取积分商品分类分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getPointsGoodsCategoryPages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条积分商品分类信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 分类信息
     */
    public function getPointsGoodsCategoryInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取积分商品分类数量
     * 
     * @param array $condition 查询条件
     * @return int 分类数量
     */
    public function getPointsGoodsCategoryCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取积分商品分类列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getPointsGoodsCategoryColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

}
